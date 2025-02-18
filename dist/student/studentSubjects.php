<?php
session_start();
include '../../config/db.php';

$user_id = $_SESSION['user_id'];  // Assuming the user_id is stored in the session

$search = isset($_POST['search']) ? $_POST['search'] : '';

// Step 1: Get the student_id associated with the user_id
$student_sql = "SELECT student_id FROM students WHERE user_id = ?";
$stmt = $connection->prepare($student_sql);
$stmt->bind_param('i', $user_id);  // 'i' indicates that $user_id is an integer
$stmt->execute();
$result = $stmt->get_result();

// Fetch student_id
$student = $result->fetch_assoc();

if ($student) {
    $student_id = $student['student_id'];  // Now you have the student_id

    // Step 2: Get the current subjects the student is enrolled in
    $sql = "
    SELECT s.subject_name, s.subject_code
    FROM subjects s
    JOIN student_enrollment e ON JSON_CONTAINS(e.subjectEnrolled, JSON_QUOTE(CAST(s.subject_id AS CHAR)))
    WHERE e.student_id = ?
    AND e.status = 'enrolled'
    ORDER BY s.subject_name
    ";

    // Prepare and execute the query
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $student_id);  // 'i' indicates that $student_id is an integer
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    echo "Student not found.";
    exit();
}

$connection->close();  // Close the connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function filterTable() {
                const searchValue = document.getElementById("search-box").value.toLowerCase();
                const tableRows = document.querySelectorAll("tbody tr");

                tableRows.forEach((row) => {
                    const subjectName = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    const subjectCode = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

                    if (subjectName.includes(searchValue) || subjectCode.includes(searchValue)) {
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

</head>
<body class="bg-[#f7f7f7] min-h-screen">


    
    <?php include './components/navbar.php' ?>


    <div class="max-w-7xl mx-auto py-14 px-4">


    <div class="flex items-center justify-between w-full ">
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li>Subjects</li>
        </ul>
        </div>

      
 
        <div class="relative max-w-sm">
                <input id="search-box" class="w-full py-2 px-4 border border-neutral-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="search" placeholder="Search">
                <button id="search-btn" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-gray-100 border border-neutral-200 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.795 13.408l5.204 5.204a1 1 0 01-1.414 1.414l-5.204-5.204a7.5 7.5 0 111.414-1.414zM8.5 14A5.5 5.5 0 103 8.5 5.506 5.506 0 008.5 14z" />
                </svg>
            </button>
        </div>

           

        

       

    </div>

    <div class="p-7 bg-white rounded-md space-y-4 mt-7">
           

        <p class="text-xl font-semibold">Current Enrolled Subjects</p>

        <div class="border-b border-neutral-100"></div>

        <div class="flex flex-col ">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table
                class="min-w-full text-left text-sm font-light text-surface ">
                <thead
                    class="border-b border-neutral-200 font-medium">
                    <tr>
                    <th scope="col" class="px-6 py-4">Subject name</th>
                    <th scope="col" class="px-6 py-4">Subject Code</th>
                    <!-- <th scope="col" class="px-6 py-4">Course Title</th>
                    <th scope="col" class="px-6 py-4">Units</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                            if ($result->num_rows > 0) {
                                    while ($subject = $result->fetch_assoc()) {
                                    echo '<tr class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100">';
                                    echo '<td class="whitespace-nowrap px-6 py-4 font-medium">' . $subject['subject_name'] . '</td>';
                                    echo '<td class="whitespace-nowrap px-6 py-4 font-medium">' . $subject['subject_code'] . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                        echo "<tr><td colspan='2' class='px-6 py-4 text-center'>No current subjects found for this student.</td></tr>";
                        }
                    ?>
                </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
            

    </div>



    </div>

    
</body>

</html>