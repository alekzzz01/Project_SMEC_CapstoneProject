<?php

session_start();

require_once '../../auth/session.php';


// Check if user is logged in and otp is verified
if (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
    // Redirect to OTP page if OTP hasn't been verified yet
    header('Location: ../../auth/otpAuth.php');
    exit();
}

include '../../config/db.php';

$user = $_SESSION['user_id'];


// fetch grades and blob data
$sql = "SELECT sec.grade_level, sy.school_year, sgr.pdf_blob, sgr.report_date, sgr.report_id
        FROM student_grade_reports sgr 
        LEFT JOIN sections sec ON sec.section_id = sgr.section_id
        LEFT JOIN school_year sy ON sy.school_year_id = sgr.school_year_id
        WHERE student_id = (SELECT student_id FROM students WHERE user_id = ?)";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$result = $stmt->get_result();

$grades = [];
while ($row = $result->fetch_assoc()) {
    $grades[] = $row;
}

// echo '<pre>';
// print_r($grades);
// echo '</pre>';






?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>

</head>

<body class="bg-[#f7f7f7] min-h-screen">


    <?php include './components/navbar.php' ?>


    <div class="max-w-7xl mx-auto py-14 px-4">


        <div class="flex items-center justify-between w-full ">

            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li>Grades</li>
                </ul>
            </div>





        </div>

        <div class=" bg-white rounded-2xl space-y-6 mt-7 shadow">

            <!-- <div class="grid grid-cols-2 gap-2">
                <p><strong>Student ID:</strong> <?php echo $studentDetails['student_id']; ?></p>
                <p class="font-semibold col-span-2">Name: <span class="font-normal"><?php echo htmlspecialchars($studentDetails['first_name'] . ' ' . $studentDetails['last_name']); ?></span></p>
                <p class="font-semibold">Date of Birth: <span class="font-normal"><?php echo htmlspecialchars($studentDetails['dob']); ?></span></p>
                <p class="font-semibold">Gender: <span class="font-normal"><?php echo htmlspecialchars($studentDetails['gender']); ?></span></p>
                <p class="font-semibold">Grade: <span class="font-normal"><?php echo htmlspecialchars($studentDetails['grade_level']); ?></span></p>
                <p class="font-semibold">Section: <span class="font-normal"><?php echo htmlspecialchars($studentDetails['section']); ?></span></p>
                <p class="font-semibold">School Year: <span class="font-normal"><?php echo htmlspecialchars($studentDetails['school_year']); ?></span></p>
            </div>


            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse border border-gray-300 bg-white shadow-md">
                    <thead>
                        <tr>
                            <th rowspan="2" class="border border-gray-300 px-4 py-2 bg-gray-200 text-left">Learning Areas</th>
                            <th colspan="4" class="border border-gray-300 px-4 py-2 bg-gray-200">Quarter</th>
                            <th rowspan="2" class="border border-gray-300 px-4 py-2 bg-gray-200">Final Grade</th>
                            <th rowspan="2" class="border border-gray-300 px-4 py-2 bg-gray-200">Remarks</th>
                        </tr>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 bg-gray-200">1</th>
                            <th class="border border-gray-300 px-4 py-2 bg-gray-200">2</th>
                            <th class="border border-gray-300 px-4 py-2 bg-gray-200">3</th>
                            <th class="border border-gray-300 px-4 py-2 bg-gray-200">4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Group grades by subject
                        $subjects = [];
                        foreach ($grades as $grade) {
                            $subjects[$grade['subject_name']][] = $grade;
                        }

                        // Initialize variables for General Average
                        $total_final_grades = 0;
                        $total_subjects = 0;

                        // Display grades for each subject and calculate final grades
                        foreach ($subjects as $subject => $subject_grades) {
                            echo "<tr>";
                            echo "<td class='border border-gray-300 px-4 py-2 text-left'>" . htmlspecialchars($subject) . "</td>";

                            // Initialize variables for each quarter
                            $quarter_1 = $quarter_2 = $quarter_3 = $quarter_4 = 0;
                            $total_grade = 0;
                            $quarters_count = 0;

                            // Iterate over the grades to assign them to the appropriate quarter
                            foreach ($subject_grades as $grade) {
                                $roundedGrade = round($grade['grade']);

                                switch ($grade['Quarter']) {
                                    case '1st':
                                    case '1':
                                        $quarter_1 = $roundedGrade;
                                        break;
                                    case '2nd':
                                    case '2':
                                        $quarter_2 = $roundedGrade;
                                        break;
                                    case '3rd':
                                    case '3':
                                        $quarter_3 = $roundedGrade;
                                        break;
                                    case '4th':
                                    case '4':
                                        $quarter_4 = $roundedGrade;
                                        break;
                                }

                                // Add to total grade and increment the count
                                $total_grade += $roundedGrade;
                                $quarters_count++;
                            }

                            // Calculate the final grade (average of all quarters)
                            $final_grade = $quarters_count > 0 ? round($total_grade / $quarters_count) : 0;

                            // Add final grade to total for general average
                            $total_final_grades += $final_grade;
                            $total_subjects++;

                            // Determine remarks based on final grade
                            $remarks = $final_grade >= 75 ? "Passed" : "Failed";

                            // Output the grades for each quarter
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_1) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_2) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_3) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_4) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($final_grade) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($remarks) . "</td>";
                            echo "</tr>";
                        }

                        // Calculate general average
                        $general_average = $total_subjects > 0 ? round($total_final_grades / $total_subjects) : 0;
                        ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-left font-bold">General Average</td>
                            <td colspan="4" class="border border-gray-300 px-4 py-2"></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($general_average); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($general_average >= 75 ? "Passed" : "Failed"); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>  -->


            <div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">YEAR/SEMESTER</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Grade</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Download</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($grade['school_year']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($grade['grade_level']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">New</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                    <a href="./functions/generate_Report.php?id=<?=urlencode($grade['report_id']) ?>" target="_blank" class="text-blue-600 hover:underline">View PDF</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>



        </div>



    </div>


</body>

</html>