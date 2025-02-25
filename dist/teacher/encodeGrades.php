<?php
include '../../config/db.php';
session_start();

$section = isset($_GET['section']) ? $_GET['section'] : '';  
$teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : (isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '');

echo "Section: " . htmlspecialchars($section) . "<br>";
echo "Teacher ID: " . htmlspecialchars($teacher_id) . "<br>";
// Capture section_id from URL
$section_id = isset($_GET['section_id']) ? $_GET['section_id'] : '';  

// Display the Section ID if it's passed through the URL
echo "Section ID: " . htmlspecialchars($section_id) . "<br>";

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
    LEFT JOIN schedules sch ON sec.section_name = sch.section  
    LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
    LEFT JOIN teachers t ON sec.adviser_id = t.teacher_id
    WHERE sec.section_name = ? AND sec.adviser_id = ?
";

// Prepare and execute the query with both section and teacher_id
$stmt = $connection->prepare($query);
$stmt->bind_param("si", $section, $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query returned any results
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Fetch the first row (assuming only one result)
} else {
    echo "Section or adviser data not found.";
    exit();
}

// Fetch subject_id
$subject_id = $row['subject_id']; // Ensure that subject_id is fetched from the query

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
$section_check_stmt->bind_param('s', $section); // Use 's' for string
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
    LEFT JOIN schedules sch ON sec.section_name = sch.section
    LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
    WHERE sec.section_name = ? AND sec.adviser_id = ?
";

// Prepare and execute the query for students
$student_stmt = $connection->prepare($student_query);
$student_stmt->bind_param("si", $section, $teacher_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();

// Fetch all students into the $students array
$students = [];
if ($student_result->num_rows > 0) {
    while ($student_row = $student_result->fetch_assoc()) {
        $students[] = $student_row;  // Add each student to the array
    }
} else {
    echo "No students found for the selected section.";
}

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the grades from the form input
    $grades1 = $_POST['grade1'];
    $grades2 = $_POST['grade2'];
    $grades3 = $_POST['grade3'];
    $grades4 = $_POST['grade4'];

    // Loop through the students and save the grades
    foreach ($students as $index => $student) {
        // Check if the student_id is set and valid
        if (!isset($student['student_id']) || empty($student['student_id'])) {
            // Skip this iteration if the student_id is invalid
            continue;
        }

        $grade1 = $grades1[$index];
        $grade2 = $grades2[$index];
        $grade3 = $grades3[$index];
        $grade4 = $grades4[$index];

        // Calculate the final grade as the average of the 4 quarters
        $final_grade = ($grade1 + $grade2 + $grade3 + $grade4) / 4;

        // Determine the remark based on the final grade
        $remark = ($final_grade >= 75) ? 'Pass' : 'Fail';

        $student_id = $student['student_id']; // Make sure student_id is being set

        // Insert grades into the database (or update if needed)
        $query = "
            INSERT INTO student_grades (student_id, subject_id, grade1, grade2, grade3, grade4, final_grade, remark, section_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $connection->prepare($query);

        // Bind all 9 parameters (student_id, subject_id, grade1, grade2, grade3, grade4, final_grade, remark, section_id)
        $stmt->bind_param('iiiddddsi', $student_id, $subject_id, $grade1, $grade2, $grade3, $grade4, $final_grade, $remark, $section_id);
        $stmt->execute();
    }

    // Redirect to the same page after saving grades to avoid resubmission on refresh
    header("Location: encodeGrades.php?section=" . $_GET['section']);
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

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>



    <html data-theme="light">

    </html>

</head>

<body class="min-h-screen bg-[#f2f5f8]">


    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-0 h-full space-y-7">


        <div class="rounded bg-green-100 p-4 mb-7 space-y-2 shadow">
            <h1 class="font-extrabold text-4xl text-green-900"><?php 
            echo $row['subject_name']; 
            ?></h1>

            <h2 class="text-green-900 font-semibold text-xl"><?php 
            echo "Grade & Section: " . htmlspecialchars($row['grade_level']) . " - " . htmlspecialchars($row['section_name']);
            ?></h2>

            <p class="text-green-800 text-sm italic">Adviser: <?php 
            echo "Prof. " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']);
            ?></p>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">

                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Term</option>
                        <option value="">1st Quarter</option>
                        <option value="">2nd Quarter</option>
                        <option value="">3rd Quarter</option>
                        <option value="">4th Quarter</option>
                        <option value="">Final Grade</option>
                    </select>


                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Order</option>
                        <option value="">Name</option>
                        <option value="">Student No.</option>
                        <option value="">No.</option>
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

                    <button type="button" class="flex items-center justify-center text-teal-700 hover:text-white border border-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-md text-sm px-4 py-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>

                        Export
                    </button>

    <form method="POST" action="">      
    <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">          
                    <button type="submit" id="submitGrades" class="flex items-center justify-center text-white bg-teal-700  border border-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-md text-sm px-4 py-2 ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>


                        Submit Grades
                    </button>


                </div>


            </div>

            <div class=" relative overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Student No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Name</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Course</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 1</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 2</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 3</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 4</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Seatwork</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Assignment</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Project</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Attendance</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Recitation</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Examination</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Grade</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">20241001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">John Doe</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Grade 9 - Lapiz</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

            <div class=" relative overflow-x-auto">
                
                    <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">No.</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Student No.</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Name</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Subject</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">1st Quarter Grade</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">2nd Quarter Grade</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">3rd Quarter Grade</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">4th Quarter Grade</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Final Grade</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Remark</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($students)) { $counter = 1; foreach ($students as $student): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo $counter++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student['student_number']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student['first_name']) . " " . htmlspecialchars($student['last_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo htmlspecialchars($student['subject_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="grade1[]" class="input input-sm w-16 input-bordered" min="0" max="100">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="grade2[]" class="input input-sm w-16 input-bordered" min="0" max="100">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="grade3[]" class="input input-sm w-16 input-bordered" min="0" max="100">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="grade4[]" class="input input-sm w-16 input-bordered" min="0" max="100">
                                    </td>
                                </tr>
                            <?php endforeach; } else { echo "<tr><td colspan='10' class='text-center text-red-600'>No students found for the selected section.</td></tr>"; } ?>
                        </tbody>
                    
                        </tbody>
                    </table>

                    
             
            </div>
    </form>
            <?php
            // Fetch the grades from the database
            $query = "SELECT * FROM student_grades WHERE section_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $section);
            $stmt->execute();
            $result = $stmt->get_result();
            $grades = $result->fetch_all(MYSQLI_ASSOC);
            ?>

            <div class="relative overflow-x-auto">                 
            <table id="example" class="min-w-full divide-y divide-gray-200">
                <thead class="border border-gray-300 text-sm">
                    <tr>
                        <th class="py-3 px-4 text-left">No.</th>
                        <th class="py-3 px-4 text-left">Student No.</th>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Course</th>
                        <th class="py-3 px-4 text-left">1st Quarter Grade</th> <!-- New column -->
                        <th class="py-3 px-4 text-left">2nd Quarter Grade</th> <!-- New column -->
                        <th class="py-3 px-4 text-left">3rd Quarter Grade</th> <!-- New column -->
                        <th class="py-3 px-4 text-left">4th Quarter Grade</th> <!-- New column -->
                        <th class="py-3 px-4 text-left">Final Grade</th> <!-- New column -->
                        <th class="py-3 px-4 text-left">Remarks</th> <!-- New column -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= $counter++ ?></td> <!-- Display the counter number -->
                    <td><?= htmlspecialchars($grade['student_number']) ?></td>
                    <td><?= htmlspecialchars($grade['first_name'] . ' ' . $grade['last_name']) ?></td>
                    <td><?= htmlspecialchars($grade['subject_name']) ?></td>
                    <td><?= htmlspecialchars($grade['grade1']) ?></td>
                    <td><?= htmlspecialchars($grade['grade2']) ?></td>
                    <td><?= htmlspecialchars($grade['grade3']) ?></td>
                    <td><?= htmlspecialchars($grade['grade4']) ?></td>
                    <td><?= htmlspecialchars($grade['final_grade']) ?></td>
                    <td><?= htmlspecialchars($grade['remark']) ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
            </table>



            </div>           
        </div>



    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>