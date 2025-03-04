<?php
include '../../config/db.php';
session_start();

$section = isset($_GET['section']) ? $_GET['section'] : '';  
$teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : (isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '');
$section_id = isset($_GET['section_id']) ? $_GET['section_id'] : '';
$selected_quarter = isset($_POST['selected_quarter']) ? $_POST['selected_quarter'] : (isset($_GET['quarter']) ? $_GET['quarter'] : '1');
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';

if (empty($section_id) && !empty($section)) {
    $section_id_query = "SELECT section_id FROM sections WHERE section_name = ?";
    $section_id_stmt = $connection->prepare($section_id_query);
    $section_id_stmt->bind_param('s', $section);
    $section_id_stmt->execute();
    $section_id_result = $section_id_stmt->get_result();
    
    if ($section_id_result->num_rows > 0) {
        $section_id_row = $section_id_result->fetch_assoc();
        $section_id = $section_id_row['section_id'];
        echo "Retrieved Section ID from database: " . htmlspecialchars($section_id) . "<br>";
    } else {
        echo "Section ID not found for section name: " . htmlspecialchars($section);
        exit();
    }
}

// Query to fetch the section, subject, and adviser information
$query = "
    SELECT sec.section_name, sec.grade_level, sub.subject_name, sub.subject_id, 
           t.first_name, t.last_name
    FROM sections sec
    LEFT JOIN schedules sch ON sec.section_id = sch.section_id  
    LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
    LEFT JOIN teachers t ON sec.adviser_id = t.teacher_id
    WHERE sec.section_name = ? AND sec.adviser_id = ? AND sub.subject_id = ?
";

$stmt = $connection->prepare($query);
$stmt->bind_param("sii", $section, $teacher_id, $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Section or adviser data not found.";
    exit();
}

$subject_id = $row['subject_id'];

// Ensure the subject_id exists in the subjects table
$subject_check_query = "SELECT COUNT(*) FROM subjects WHERE subject_id = ?";
$subject_check_stmt = $connection->prepare($subject_check_query);
$subject_check_stmt->bind_param('i', $subject_id);
$subject_check_stmt->execute();
$subject_check_result = $subject_check_stmt->get_result();
$subject_check_row = $subject_check_result->fetch_row();

if ($subject_check_row[0] == 0) {
    echo "Invalid subject ID!";
    exit();
}

// Ensure the section_id exists in the sections table
$section_check_query = "SELECT COUNT(*) FROM sections WHERE section_name = ?";
$section_check_stmt = $connection->prepare($section_check_query);
$section_check_stmt->bind_param('s', $section);
$section_check_stmt->execute();
$section_check_result = $section_check_stmt->get_result();
$section_check_row = $section_check_result->fetch_row();

if ($section_check_row[0] == 0) {
    echo "Invalid section ID!";
    exit();
}

// Query to fetch students for the selected section along with their subject name
$student_query = "
    SELECT s.student_id, s.student_number, s.first_name, s.last_name, sub.subject_id, sub.subject_name
    FROM student_enrollment se
    LEFT JOIN students s ON se.student_id = s.student_id
    LEFT JOIN sections sec ON se.section = sec.section_id
    LEFT JOIN schedules sch ON sec.section_id = sch.section_id
    LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
    WHERE sec.section_name = ? AND sec.adviser_id = ? AND sub.subject_id = ?
";

$student_stmt = $connection->prepare($student_query);
$student_stmt->bind_param("sii", $section, $teacher_id, $subject_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();

$students = [];
if ($student_result->num_rows > 0) {
    while ($student_row = $student_result->fetch_assoc()) {
        $students[] = $student_row;
    }
} else {
    echo "No students found for the selected section.";
}

// Fetch the grades from the database with student information
$grades_query = "
    SELECT sg.*, s.student_number, s.first_name, s.last_name, sub.subject_name
    FROM student_grades sg
    JOIN students s ON sg.student_id = s.student_id
    JOIN subjects sub ON sg.subject_id = sub.subject_id
    WHERE sg.section_id = ? AND sg.subject_id = ?
";
$grades_stmt = $connection->prepare($grades_query);
$grades_stmt->bind_param("si", $section_id, $subject_id);
$grades_stmt->execute();
$grades_result = $grades_stmt->get_result();
$grades = [];
$graded_student_quarters = []; // Track which quarters have been graded for each student

if ($grades_result->num_rows > 0) {
    while ($grade_row = $grades_result->fetch_assoc()) {
        $grades[] = $grade_row;
        
        // Track which quarters have been graded for each student
        for ($i = 1; $i <= 4; $i++) {
            $grade_field = 'grade' . $i;
            if (isset($grade_row[$grade_field]) && $grade_row[$grade_field] !== null) {
                $graded_student_quarters[$grade_row['student_id']][$i] = true;
            }
        }
    }
}

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_grades'])) {
    // Get the quarter from the form
    $quarter = $_POST['selected_quarter'];
    $grade_field = 'grade' . $quarter;
    
    // Get the student IDs and grades
    $student_ids = $_POST['student_id'];
    $quarter_grades = $_POST[$grade_field];
    
    // Loop through the submitted student IDs and their grades
    foreach ($student_ids as $index => $student_id) {
        // Skip if student already has grades for this quarter
        if (isset($graded_student_quarters[$student_id][$quarter])) {
            continue;
        }
        
        $quarter_grade = $quarter_grades[$index];
        
        // Check if student already has a record in student_grades
        $check_query = "SELECT * FROM student_grades WHERE student_id = ? AND subject_id = ? AND section_id = ?";
        $check_stmt = $connection->prepare($check_query);
        $check_stmt->bind_param('iis', $student_id, $subject_id, $section_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Update existing record
            $update_query = "UPDATE student_grades SET $grade_field = ? WHERE student_id = ? AND subject_id = ? AND section_id = ?";
            $update_stmt = $connection->prepare($update_query);
            $update_stmt->bind_param('diis', $quarter_grade, $student_id, $subject_id, $section_id);
            $update_stmt->execute();
        } else {
            // Insert new record with only this quarter's grade
            $insert_query = "INSERT INTO student_grades (student_id, subject_id, $grade_field, section_id) VALUES (?, ?, ?, ?)";
            $insert_stmt = $connection->prepare($insert_query);
            $insert_stmt->bind_param('idis', $student_id, $subject_id, $quarter_grade, $section_id);
            $insert_stmt->execute();
        }
        
        // Update final grade and remark if all quarters are available
        $update_final_query = "
            UPDATE student_grades 
            SET final_grade = (IFNULL(grade1, 0) + IFNULL(grade2, 0) + IFNULL(grade3, 0) + IFNULL(grade4, 0)) / 
                (CASE 
                    WHEN grade1 IS NOT NULL THEN 1 ELSE 0 END + 
                    CASE WHEN grade2 IS NOT NULL THEN 1 ELSE 0 END + 
                    CASE WHEN grade3 IS NOT NULL THEN 1 ELSE 0 END + 
                    CASE WHEN grade4 IS NOT NULL THEN 1 ELSE 0 END
                ),
                remark = CASE 
                    WHEN (IFNULL(grade1, 0) + IFNULL(grade2, 0) + IFNULL(grade3, 0) + IFNULL(grade4, 0)) / 
                        (CASE 
                            WHEN grade1 IS NOT NULL THEN 1 ELSE 0 END + 
                            CASE WHEN grade2 IS NOT NULL THEN 1 ELSE 0 END + 
                            CASE WHEN grade3 IS NOT NULL THEN 1 ELSE 0 END + 
                            CASE WHEN grade4 IS NOT NULL THEN 1 ELSE 0 END
                        ) >= 75 THEN 'Pass' ELSE 'Fail' END
            WHERE student_id = ? AND subject_id = ? AND section_id = ?
        ";
        
        $update_final_stmt = $connection->prepare($update_final_query);
        $update_final_stmt->bind_param('iis', $student_id, $subject_id, $section_id);
        $update_final_stmt->execute();
    }
    
    // Redirect to avoid form resubmission
    header("Location: encodeGrades.php?section=" . urlencode($section) . "&teacher_id=" . urlencode($teacher_id) . "&section_id=" . urlencode($section_id) . "&quarter=" . urlencode($quarter) . "&subject_id=" . urlencode($subject_id));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encoding of Grades</title>

    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="../../assets/js/script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <html data-theme="light"></html>
</head>

<body class="min-h-screen bg-[#f2f5f8]">
    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-0 h-full space-y-7">
        <div class="rounded bg-green-100 p-4 mb-7 space-y-2 shadow">
            <h1 class="font-extrabold text-4xl text-green-900"><?php echo $row['subject_name']; ?></h1>
            <h2 class="text-green-900 font-semibold text-xl"><?php echo "Grade & Section: " . htmlspecialchars($row['grade_level']) . " - " . htmlspecialchars($row['section_name']); ?></h2>
            <p class="text-green-800 text-sm italic">Adviser: <?php echo "Prof. " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></p>
        </div>

        <div class="space-y-4">
            <form method="POST" action="">
                <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <select name="selected_quarter" class="select select-bordered select-sm" onchange="this.form.submit()">
                            <option disabled>Select Quarter</option>
                            <option value="1" <?= $selected_quarter == '1' ? 'selected' : '' ?>>1st Quarter</option>
                            <option value="2" <?= $selected_quarter == '2' ? 'selected' : '' ?>>2nd Quarter</option>
                            <option value="3" <?= $selected_quarter == '3' ? 'selected' : '' ?>>3rd Quarter</option>
                            <option value="4" <?= $selected_quarter == '4' ? 'selected' : '' ?>>4th Quarter</option>
                        </select>

                        <select class="select select-bordered select-sm">
                            <option disabled selected value="">Order</option>
                            <option value="name">Name</option>
                            <option value="student_no">Student No.</option>
                            <option value="no">No.</option>
                        </select>

                        <label class="input input-sm input-bordered flex items-center gap-2">
                            <input type="text" class="grow" placeholder="Search" />
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 16 16"
                                fill="currentColor"
                                class="h-4 w-4 opacity-70">
                                <path
                                    fill-rule="evenodd"
                                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </label>
                    </div>

                    <div class="flex items-center gap-4">
                        <?php
                        // Check if there are any students who haven't been graded for the current quarter
                        $quarter_field = 'grade' . $selected_quarter;
                        $ungraded_for_quarter = false;
                        
                        foreach ($students as $student) {
                            if (!isset($graded_student_quarters[$student['student_id']][$selected_quarter])) {
                                $ungraded_for_quarter = true;
                                break;
                            }
                        }
                        
                        // Only show submit button if there are ungraded students for the selected quarter
                        if ($ungraded_for_quarter) {
                        ?>
                        <button type="submit" name="submit_grades" class="flex items-center justify-center text-white bg-teal-700 border border-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-md text-sm px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Submit <?= $selected_quarter ?>st Quarter Grades
                        </button>
                        <?php } ?>
                    </div>
                </div>

                <!-- Input Grade Table -->
                <div class="relative overflow-x-auto mt-6">
                    <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">No.</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Student No.</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Name</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Subject</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500"><?= $selected_quarter ?>st Quarter Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            // Filter students who haven't been graded for the selected quarter
                            $quarter_to_grade = [];
                            $counter = 1;
                            
                            foreach ($students as $student) {
                                $already_graded = isset($graded_student_quarters[$student['student_id']][$selected_quarter]);
                                
                                if (!$already_graded) {
                                    $quarter_to_grade[] = $student;
                                }
                            }
                            
                            if (!empty($quarter_to_grade)) {
                                foreach ($quarter_to_grade as $student) {
                            ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo $counter++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student['student_number']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student['first_name']) . " " . htmlspecialchars($student['last_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student['subject_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="hidden" name="student_id[]" value="<?php echo $student['student_id']; ?>">
                                        <input type="number" name="grade<?= $selected_quarter ?>[]" class="input input-sm w-16 input-bordered" min="0" max="100" required>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center px-6 py-4 text-gray-500'>All students have already been graded for the " . $selected_quarter . "Quarter.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Submitted Grades Display Table -->
            <div class="relative overflow-x-auto mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Submitted Grades</h3>
                <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Student No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Name</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Subject</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">1st Quarter</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">2nd Quarter</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">3rd Quarter</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">4th Quarter</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Final Grade</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Remark</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        // Display all students with their grades - submitted or N/A
                        if (!empty($students)) {
                            $counter = 1;
                            // First get all student records with their grades
                            $all_student_grades = [];
                            
                            // Prepare the array with all students
                            foreach ($students as $student) {
                                $all_student_grades[$student['student_id']] = [
                                    'student_id' => $student['student_id'],
                                    'student_number' => $student['student_number'],
                                    'first_name' => $student['first_name'],
                                    'last_name' => $student['last_name'],
                                    'subject_name' => $student['subject_name'],
                                    'grade1' => 'N/A',
                                    'grade2' => 'N/A',
                                    'grade3' => 'N/A',
                                    'grade4' => 'N/A',
                                    'final_grade' => 'N/A',
                                    'remark' => 'N/A'
                                ];
                            }
                            
                            // Overlay with actual grades from database
                            foreach ($grades as $grade) {
                                for ($i = 1; $i <= 4; $i++) {
                                    $grade_field = 'grade' . $i;
                                    if (isset($grade[$grade_field]) && $grade[$grade_field] !== null) {
                                        $all_student_grades[$grade['student_id']][$grade_field] = $grade[$grade_field];
                                    }
                                }
                                
                                if ($grade['final_grade'] !== null) {
                                    $all_student_grades[$grade['student_id']]['final_grade'] = number_format($grade['final_grade'], 2);
                                }
                                
                                if ($grade['remark'] !== null) {
                                    $all_student_grades[$grade['student_id']]['remark'] = $grade['remark'];
                                }
                            }
                            
                            // Display all students with their grades
                            foreach ($all_student_grades as $student_id => $student_record) {
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo $counter++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student_record['student_number']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student_record['first_name']) . " " . htmlspecialchars($student_record['last_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student_record['subject_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo $student_record['grade1']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo $student_record['grade2']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo $student_record['grade3']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo $student_record['grade4']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo $student_record['final_grade']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?php echo $student_record['remark'] == 'Pass' ? 'text-green-600' : ($student_record['remark'] == 'Fail' ? 'text-red-600' : 'text-gray-500'); ?>">
                                    <?php echo $student_record['remark']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="./functions/generateGradesReport.php?section=<?= urlencode($section) ?>&teacher_id=<?= urlencode($teacher_id) ?>&section_id=<?= urlencode($section_id) ?>&student_id=<?= urlencode($student_id) ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="11" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No students found for this section.</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to enhance the form submission
        document.addEventListener('DOMContentLoaded', function() {
            // You can add any client-side validation here
            const gradeInputs = document.querySelectorAll('input[type="number"]');
            gradeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 0) this.value = 0;
                    if (this.value > 100) this.value = 100;
                });
            });
        });
    </script>
</body>
</html>