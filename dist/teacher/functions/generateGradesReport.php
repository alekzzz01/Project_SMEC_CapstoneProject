<?php
include '../../../config/db.php';  
session_start();
require '../../../vendor/autoload.php'; // Load Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

// Get parameters from URL
$section = isset($_GET['section']) ? $_GET['section'] : '';  
$teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '';
$section_id = isset($_GET['section_id']) ? $_GET['section_id'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

if (empty($section) || empty($teacher_id) || empty($section_id)) {
    die('Missing required parameters');
}

// Query to fetch the section, grade level and adviser information
$section_query = "
    SELECT sec.section_name, sec.grade_level, 
           t.first_name, t.last_name,
           sy.school_year, sy.school_year_id
    FROM sections sec
    LEFT JOIN teachers t ON sec.adviser_id = t.teacher_id
    LEFT JOIN school_year sy ON sy.school_year_id = 
        (SELECT MAX(school_year_id) FROM school_year WHERE status = 'Open')
    WHERE sec.section_name = ? AND sec.adviser_id = ?
";

$section_stmt = $connection->prepare($section_query);
$section_stmt->bind_param("si", $section, $teacher_id);
$section_stmt->execute();
$section_result = $section_stmt->get_result();

if ($section_result->num_rows > 0) {
    $section_info = $section_result->fetch_assoc();
} else {
    die("Section information not found.");
}

$school_year_id = $section_info['school_year_id'];

// Check if the student_grade_reports table exists, create it if not
$check_table_query = "
    CREATE TABLE IF NOT EXISTS student_grade_reports (
        report_id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        section_id INT NOT NULL,
        school_year_id INT NOT NULL,
        report_date DATETIME NOT NULL,
        pdf_blob MEDIUMBLOB NOT NULL,
        INDEX (student_id),
        INDEX (section_id),
        INDEX (school_year_id)
    )
";
$connection->query($check_table_query);

// If a specific student_id is provided, handle single student view
if (!empty($student_id)) {
    // Check if a report already exists for this student
    $check_existing = "
        SELECT report_id, pdf_blob FROM student_grade_reports 
        WHERE student_id = ? AND section_id = ? AND school_year_id = ?
        ORDER BY report_date DESC LIMIT 1
    ";
    $check_stmt = $connection->prepare($check_existing);
    $check_stmt->bind_param("iis", $student_id, $section_id, $school_year_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Report exists, retrieve and display it
        $report_data = $check_result->fetch_assoc();
        $pdf_blob = $report_data['pdf_blob'];
        
        // Output the PDF directly to the browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Student_Grade_Report.pdf"');
        echo $pdf_blob;
        exit;
    } else {
        // No report exists yet, so we need to generate it
        // Fetch this student's information and grades
        $student_query = "
            SELECT s.student_id, s.student_number, s.first_name, s.middle_initial, s.last_name, 
                sub.subject_code, sub.subject_name, sub.description,
                sg.grade1, sg.grade2, sg.grade3, sg.grade4, sg.final_grade, sg.remark
            FROM students s
            JOIN student_enrollment se ON s.student_id = se.student_id
            JOIN sections sec ON se.section = sec.section_id
            JOIN schedules sch ON sec.section_name = sch.section
            LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
            LEFT JOIN student_grades sg ON s.student_id = sg.student_id AND sub.subject_id = sg.subject_id AND sg.section_id = ?
            WHERE sec.section_name = ? AND se.status = 'Enrolled' AND s.student_id = ?
            ORDER BY sub.subject_name
        ";
        
        $student_stmt = $connection->prepare($student_query);
        $student_stmt->bind_param("ssi", $section_id, $section, $student_id);
        $student_stmt->execute();
        $student_result = $student_stmt->get_result();
        
        // Prepare student data structure
        $student_data = null;
        while ($row = $student_result->fetch_assoc()) {
            if ($student_data === null) {
                $student_data = [
                    'student_id' => $row['student_id'],
                    'student_number' => $row['student_number'],
                    'name' => $row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'],
                    'subjects' => []
                ];
            }
            
            $student_data['subjects'][] = [
                'subject_code' => $row['subject_code'],
                'subject_name' => $row['subject_name'],
                'description' => $row['description'],
                'final_grade' => $row['final_grade'] ? number_format($row['final_grade'], 2) : 'N/A',
                'remark' => $row['remark'] ?: 'N/A'
            ];
        }
        
        if ($student_data === null) {
            die("Student not found or has no subjects assigned.");
        }
        
        // Generate the PDF for this student
        $html = generateStudentReportHTML($student_data, $section_info);
        $pdf_output = generatePDF($html);
        
        // Store the PDF in the database
        $insert_stmt = $connection->prepare("
            INSERT INTO student_grade_reports 
            (student_id, section_id, school_year_id, report_date, pdf_blob) 
            VALUES (?, ?, ?, NOW(), ?)
        ");
        
        if (!$insert_stmt) {
            die("Prepare failed: " . $connection->error);
        }
        
        $insert_stmt->bind_param("iiss", $student_id, $section_id, $school_year_id, $pdf_output);
        $result = $insert_stmt->execute();
        
        if (!$result) {
            die("Error saving PDF: " . $insert_stmt->error);
        }
        
        // Output the PDF directly to the browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Student_Grade_Report.pdf"');
        echo $pdf_output;
        exit;
    }
} else {
    // This is the bulk report generation for the teacher/admin view
    // Fetch all students in the section with their grades
    $grades_query = "
       SELECT s.student_id, s.student_number, s.first_name, s.middle_initial, s.last_name, 
           sub.subject_code, sub.subject_name, sub.description,
           sg.grade1, sg.grade2, sg.grade3, sg.grade4, sg.final_grade, sg.remark
    FROM students s
    JOIN student_enrollment se ON s.student_id = se.student_id
    JOIN sections sec ON se.section = sec.section_id
    JOIN schedules sch ON sec.section_name = sch.section
    LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
    LEFT JOIN student_grades sg ON s.student_id = sg.student_id AND sub.subject_id = sg.subject_id AND sg.section_id = ?
    WHERE sec.section_name = ? AND se.status = 'Enrolled'
    ORDER BY s.last_name, s.first_name, sub.subject_name
    ";

    $grades_stmt = $connection->prepare($grades_query);
    $grades_stmt->bind_param("ss", $section_id, $section);
    $grades_stmt->execute();
    $grades_result = $grades_stmt->get_result();

    $students_grades = [];
    while ($row = $grades_result->fetch_assoc()) {
        $student_id = $row['student_id'];
        $subject_code = $row['subject_code'];
        
        if (!isset($students_grades[$student_id])) {
            $students_grades[$student_id] = [
                'student_id' => $row['student_id'],
                'student_number' => $row['student_number'],
                'name' => $row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'],
                'subjects' => []
            ];
        }
        
        $students_grades[$student_id]['subjects'][] = [
            'subject_code' => $row['subject_code'],
            'subject_name' => $row['subject_name'],
            'description' => $row['description'],
            'final_grade' => $row['final_grade'] ? number_format($row['final_grade'], 2) : 'N/A',
            'remark' => $row['remark'] ?: 'N/A'
        ];
    }

    // Generate HTML for all students
    $all_html = "";
    $date = date('F d, Y h:i A');

    // Prepare statement for BLOB insertion
    $insert_stmt = $connection->prepare("
        INSERT INTO student_grade_reports 
        (student_id, section_id, school_year_id, report_date, pdf_blob) 
        VALUES (?, ?, ?, NOW(), ?)
    ");
    if (!$insert_stmt) {
        die("Prepare failed: " . $connection->error);
    }

    // Create a PDF for each student
    foreach ($students_grades as $student_id => $student_data) {
        // Check if a report already exists for this student
        $check_existing = "
            SELECT report_id FROM student_grade_reports 
            WHERE student_id = ? AND section_id = ? AND school_year_id = ?
            LIMIT 1
        ";
        $check_stmt = $connection->prepare($check_existing);
        $check_stmt->bind_param("iis", $student_id, $section_id, $school_year_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        // Only create a new report if one doesn't exist
        if ($check_result->num_rows == 0) {
            $html = generateStudentReportHTML($student_data, $section_info);
            $output = generatePDF($html);
            
            // Insert the PDF into the database as a BLOB
            $insert_stmt->bind_param("iiss", $student_id, $section_id, $school_year_id, $output);
            $result = $insert_stmt->execute();
            
            if (!$result) {
                echo "Error saving PDF for student " . $student_data['name'] . ": " . $insert_stmt->error . "<br>";
            }
        }
        
        // Generate the HTML for the combined document regardless
        $html = generateStudentReportHTML($student_data, $section_info);
        $all_html .= $html . "<div style='page-break-after: always;'></div>";
    }

    // For the class adviser, create a single PDF with all students
    $dompdf = createDompdfInstance();
    $dompdf->loadHtml($all_html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Download the combined PDF for the teacher
    $dompdf->stream("All_Student_Grades_" . $section . ".pdf", ["Attachment" => 1]);
}

// Function to generate HTML for a student report
function generateStudentReportHTML($student_data, $section_info) {
    $date = date('F d, Y h:i A');
    
    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Student Grades Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 8px;
                text-align: center;
            }
    
            .header {
                width: 100%;
                border-bottom: 1px solid #e5e7eb;
                margin-bottom: 20px;
            }
    
            .header td {
                padding: 10px;
                font-size: 14px;
                vertical-align: middle;
            }
    
            .title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 4px;
                text-transform: uppercase;
                margin: 0;
            }
    
            .address {
                font-size: 12px;
                margin: 0;
            }
    
            .student-info {
                width: 100%;
                border-bottom: 1px solid #e5e7eb;
                margin-top: 20px;
                padding-bottom: 20px;
            }
            .student-info td {
                padding: 5px;
                font-size: 14px;
            }
    
            .schedule-table {
                width: 100%;
                margin-top: 20px;
                border-collapse: collapse;
                font-size: 12px;
                table-layout: fixed;
                word-wrap: break-word;
            }
    
            .schedule-table th {
                border: 1px solid #e5e7eb;
                padding: 5px;
                text-align: left;
                background-color: #f3f4f6;
                font-weight: bold;
            }
    
            .schedule-table td {
                border: 1px solid #e5e7eb;
                padding: 5px;
                text-align: left;
            }
            
            .pass {
                color: green;
                font-weight: bold;
            }
            
            .fail {
                color: red;
                font-weight: bold;
            }
        </style>
    </head>
    
    <body>
        <p style='text-align: left; font-size: 12px;'>Printed on: $date</p>
        
        <table class='header'>
            <tr>
                <td style='width: 80px; text-align: center;'>
                    <img src='http://localhost/dashboard/Projects/Project_SMEC_CapstoneProject/assets/images/smec_logo.jpg' alt='School Logo' width='80'>
                </td>
                <td style='text-align: center;'>
                    <p class='title' style='margin-bottom: 4px;'>Sta. Marta Educational Center Inc.</p>
                    <p class='address'>104 A.B. Cruz, Dolmar Subd., Kalawaan, Elizco Rd, Pasig City</p>
                    <p><strong>STUDENT GRADES REPORT</strong></p>
                </td>
                <td style='width: 80px; text-align: center;'>
                    <img src='http://localhost/dashboard/Projects/Project_SMEC_CapstoneProject/assets/images/smec_logo.jpg' alt='School Logo' width='80'>
                </td>
            </tr>
        </table>
    
        <table class='student-info'>
            <tr>
                <td><strong>Name:</strong> " . $student_data['name'] . "</td>
                <td><strong>Academic Year:</strong> " . $section_info['school_year'] . "</td> 
            </tr>
            <tr>
                <td><strong>Grade Level:</strong> " . $section_info['grade_level'] . "</td>
                <td><strong>Date:</strong> $date</td>
            </tr>
            <tr>
                <td><strong>Section:</strong> " . $section_info['section_name'] . "</td>
            </tr>
        </table>
    
        <table class='schedule-table'>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Description</th>
                    <th>Section</th>
                    <th>Final Grade</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>";
            
    foreach ($student_data['subjects'] as $subject) {
        $remark_class = strtolower($subject['remark']) == 'pass' ? 'pass' : (strtolower($subject['remark']) == 'fail' ? 'fail' : '');
        
        $html .= "
        <tr>
            <td>" . $subject['subject_code'] . "</td>
            <td>" . $subject['subject_name'] . "</td>
            <td>" . $subject['description'] . "</td>
            <td>" . $section_info['grade_level'] . " - " . $section_info['section_name'] . "</td>
            <td>" . $subject['final_grade'] . "</td>
            <td class='" . $remark_class . "'>" . $subject['remark'] . "</td>
        </tr>";
    }
            
    $html .= "
            </tbody>
        </table>
        
        <p style='text-align: right; font-size: 12px;'>Student Number: " . $student_data['student_number'] . "</p>
        
        <div style='margin-top: 50px;'>
            <table style='width: 100%;'>
                <tr>
                    <td style='width: 50%; text-align: center;'>
                        <p>_________________________</p>
                        <p style='margin-top: 5px;'>" . $section_info['first_name'] . " " . $section_info['last_name'] . "</p>
                        <p style='font-size: 12px; margin-top: 0;'>Class Adviser</p>
                    </td>
                    <td style='width: 50%; text-align: center;'>
                        <p>_________________________</p>
                        <p style='margin-top: 5px;'>School Principal</p>
                        <p style='font-size: 12px; margin-top: 0;'>School Principal</p>
                    </td>
                </tr>
            </table>
        </div>
    </body>
    </html>";
    
    return $html;
}

// Function to generate a PDF from HTML
function generatePDF($html) {
    $dompdf = createDompdfInstance();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    return $dompdf->output();
}

// Function to create a configured Dompdf instance
function createDompdfInstance() {
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    
    return new Dompdf($options);
}