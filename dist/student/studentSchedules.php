<?php
session_start();
require '../../vendor/autoload.php'; // Include Dompdf (for Composer installations)
include '../../config/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user_id'])) {
    die('User ID is not set in the session.');
}

$user = $_SESSION['user_id'];

$sql = "
    SELECT 
        s.user_id,
        s.student_id,
        sy.school_year,
        s.first_name,
        s.middle_initial,
        s.last_name,
        se.grade_level,
        sc.section_name,
        sch.day,
        sch.time_in,
        sch.time_out,
        sch.room,
        su.subject_name,
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
if ($stmt === false) {
    die('Error preparing statement: ' . $connection->error);
}


$stmt->bind_param("i", $user);
$stmt->execute();


$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;

    $name = $row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'];
    $gradeLevel = $row['grade_level'];
    $section = $row['section_name'];
    $academic_year = $row['school_year'];
}

// Print the results using print_r
// echo "<pre>";
// print_r($rows);
// echo "</pre>";

$stmt->close();
$connection->close();

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules</title>


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
                    <li>Schedules</li>
                </ul>
            </div>

            <!-- Export Button -->


            <a href="./functions/generate_schedule.php" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                Print
            </a>




        </div>

        <div class="p-7 bg-white rounded-md space-y-4 mt-7">


            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <p class="font-semibold">Name: <span class="font-normal"><?php echo $name ?></span> </p>

                    <p class="font-semibold">Grade Level: <span class="font-normal">Grade <?php echo $gradeLevel ?></span> </p>

                    <p class="font-semibold">Section: <span class="font-normal"><?php echo $section ?></span> </p>

                </div>

                <div class="space-y-4">
                    <p class="font-semibold">Academic Year: <span class="font-normal"><?php echo $academic_year ?></span> </p>



                </div>

            </div>

            <div class="border-b border-neutral-100"></div>


            <div class="flex flex-col ">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table
                                class="min-w-full text-left text-sm">
                                <thead
                                    class="border-b border-neutral-200 font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Subject</th>
                                        <th scope="col" class="px-6 py-4">Description</th>
                                        <th scope="col" class="px-6 py-4">Teacher</th>
                                        <th scope="col" class="px-6 py-4">Time</th>
                                        <th scope="col" class="px-6 py-4">Day(s)</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if (!empty($rows)) : ?>
                                        <?php foreach ($rows as $row) : ?>

                                            <tr
                                                class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100 ">
                                                <td class="whitespace-nowrap px-6 py-4 font-medium"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                                <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($row['description']); ?></td>
                                                <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($row['teacher_first_name'] . ' ' . $row['teacher_last_name']); ?></td>

                                                <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars(date("h:i A", strtotime($row['time_in'])) . ' - ' . date("h:i A", strtotime($row['time_out']))); ?></td>
                                                <td class="whitespace-nowrap px-6 py-4"><?php echo htmlspecialchars($row['day']); ?></td>
                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>
                                        <tr>
                                            <td colspan="10">No schedule found.</td>
                                        </tr>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




        </div>



    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function filterTable() {
                const searchValue = document.getElementById("search-box").value.toLowerCase();
                const tableRows = document.querySelectorAll("tbody tr");

                tableRows.forEach((row) => {
                    const subjectName = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    const teacherName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    const units = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                    const time = row.querySelector("td:nth-child(4)").textContent.toLowerCase();
                    const day = row.querySelector("td:nth-child(5)").textContent.toLowerCase();

                    // Check if any column contains the search value
                    if (
                        subjectName.includes(searchValue) ||
                        teacherName.includes(searchValue) ||
                        units.includes(searchValue) ||
                        time.includes(searchValue) ||
                        day.includes(searchValue)
                    ) {
                        row.style.display = ""; // Show row
                    } else {
                        row.style.display = "none"; // Hide row
                    }
                });
            }

            // Attach the filterTable function to the input's 'keyup' event
            const searchBox = document.getElementById("search-box");
            searchBox.addEventListener("keyup", filterTable);
        });
    </script>

</body>

</html>