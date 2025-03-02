<?php
session_start();
require '../../../config/db.php'; // Adjust path as needed
require '../../../vendor/autoload.php'; // Load Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user_id'])) {
    die('User ID is not set in the session.');
}

$user = $_SESSION['user_id'];

// Fetch Student Information and Schedule
$sql = "
    SELECT 
        s.first_name, 
        s.middle_initial, 
        s.last_name,
        s.student_number,
        sy.school_year, 
        se.grade_level, 
        sc.section_name,
        sch.day, 
        sch.time_in, 
        sch.time_out, 
        sch.day,
        sch.room,
        su.subject_name,
          su.subject_code, 
        su.description, 
        t.First_name as teacher_first_name,
        t.Last_name as teacher_last_name
    FROM students s
    INNER JOIN student_enrollment se ON s.student_id = se.student_id
    INNER JOIN school_year sy ON sy.school_year_id = se.school_year_id
    INNER JOIN sections sc ON sc.section_id = se.section
    INNER JOIN schedules sch ON sch.section_id = sc.section_id
    INNER JOIN subjects su ON su.subject_id = sch.subject_id
    INNER JOIN teachers t ON t.teacher_id = sch.teacher_id
    WHERE se.status = 'Enrolled' AND s.user_id = ?
    ORDER BY sch.time_in ASC
";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
    $student_number = $row['student_number'];
    $name = $row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'];
    $gradeLevel = $row['grade_level'];
    $section = $row['section_name'];
    $academic_year = $row['school_year'];
    $date = date('F d, Y h:i A');
}

$stmt->close();
$connection->close();

// Generate HTML for PDF
$html = "
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Student Schedule</title>
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
            font-size: 12px; /* Reduce font size */
            table-layout: fixed; /* Ensures the table doesn't exceed the page width */
            word-wrap: break-word; /* Prevents text from overflowing */
        }

        .schedule-table th {
            border: 1px solid #e5e7eb;
            padding: 5px;
            text-align: left;
            white-space: nowrap; /* Prevents text from wrapping */
            overflow: hidden;
           
        }

        .schedule-table td {
            border: 1px solid #e5e7eb;
            padding: 5px;
            text-align: left;
            overflow: hidden;
        }


        .schedule-table th {
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
                <p><strong>ONLINE CERTIFICATE OF REGISTRATION</strong></p>
            </td>
            <td style='width: 80px; text-align: center;'>
                <img src='http://localhost/dashboard/Projects/Project_SMEC_CapstoneProject/assets/images/smec_logo.jpg' alt='School Logo' width='80'>
            </td>
        </tr>
    </table>





    <table class='student-info'>
        <tr>
            <td><strong>Name:</strong> $name</td>
            <td><strong>Academic Year:</strong> $academic_year</td> 
        </tr>
        <tr>
            <td><strong>Grade Level:</strong> $gradeLevel</td>
            <td><strong>Date:</strong> $date</td>
        </tr>
        <tr>
            <td><strong>Section:</strong> $section</td>
        </tr>
    </table>




    <table class='schedule-table'>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Description</th>
                <th>Section</th>
                <th>Time</th>
                <th>Days</th>
                <th>Room</th>
              
            </tr>
        </thead>
        <tbody>";
foreach ($rows as $row) {


    $subject_code = $row['subject_code'];
    $subject_name = $row['subject_name'];
    $description = $row['description'];
    $section = $row['grade_level'] . ' - ' . $row['section_name'];
    $time = date("h:i A", strtotime($row['time_in'])) . ' - ' . date("h:i A", strtotime($row['time_out']));
    $day = $row['day'];
    $room = $row['room'];

    $html .= "
                    <tr>
                        <td>{$subject_code}</td>
                        <td>{$subject_name}</td>
                        <td>{$description}</td>
                        <td>{$section}</td>
                        <td>{$time}</td>
                        <td>{$day}</td>
                        <td>{$room}</td>
                
                    </tr>";
}
$html .= "
        </tbody>
    </table>


     <p style='text-align: right; font-size: 12px;'>Student Number: $student_number</p>

</body>

</html>";

// Configure Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Download the PDF
$dompdf->stream("Schedule_$student_number.pdf", ["Attachment" => 1]);
