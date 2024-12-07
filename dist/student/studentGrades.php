<?php
session_start();

include '../../config/db.php';

$user = $_SESSION['user_id'];

$selected_grade_level = isset($_GET['grade_level']) ? $_GET['grade_level'] : 'Grade 12'; // Default to Grade 12 if not set
    
// SQL query to fetch student details and grades information (including grade level, section, and school year)
$sql = "
    SELECT s.student_id, s.user_id, s.student_number, s.first_name, s.last_name, s.date_of_birth, s.gender, s.contact_number,
           g.grade_level, g.section, g.school_year,
           sub.subject_name, g.grade, g.Quarter
    FROM students s
    LEFT JOIN grades g ON s.student_id = g.student_id
    LEFT JOIN subjects sub ON g.subject_id = sub.subject_id
    WHERE s.user_id = ? AND g.grade_level = ?";  // Added grade_level filter


// Prepare statement
$stmt = $connection->prepare($sql);
if ($stmt === false) {
    die('Error preparing statement: ' . $connection->error);
}

// Bind the session user ID as parameter to the prepared statement

$stmt->bind_param("is", $user, $selected_grade_level); // "i" for integer, "s" for string


// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if results exist
if ($result->num_rows > 0) {
    // Fetch the student details first
    $studentDetails = [];
    $grades = [];

    while ($row = $result->fetch_assoc()) {
        $studentDetails['student_id'] = $row["student_id"];
        $studentDetails['first_name'] = $row["first_name"];
        $studentDetails['last_name'] = $row["last_name"];
        $studentDetails['gender'] = $row["gender"];
        $studentDetails['dob'] = $row["date_of_birth"];
        $studentDetails['contact_number'] = $row["contact_number"];
        $studentDetails['grade_level'] = $row["grade_level"];
        $studentDetails['section'] = $row["section"];
        $studentDetails['school_year'] = $row["school_year"];

        $grades[] = $row; // Collect grade data for later use in the table
    }

    // Calculate age from date of birth
    // $dob = new DateTime($studentDetails['dob']);
    // $currentDate = new DateTime(); // Current date and time
    // $age = $dob->diff($currentDate)->y; // 'y' returns the difference in years
    


} else {
    // Default values if no student found
    $studentDetails = array_fill_keys(['student_id', 'first_name', 'last_name', 'gender', 'dob', 'contact_number', 'grade_level', 'section', 'school_year'], '');
    $grades = [];
    // $age = '';
    
}


// Close the statement and connection
$stmt->close();
$connection->close();
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

    <html data-theme="light"></html>
   
</head>
<body class="bg-gray-100 h-screen flex flex-col">


    
    <?php include './components/navbar.php' ?>


    <div class="container mx-auto py-14 px-4 lg:px-12 h-full overflow-y-auto">


        <div class="flex items-center justify-between w-full ">
            
            <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li>Grades</li>
            </ul>
            </div>

            <div class="flex items-center gap-3"> 
                
                <!-- <label class="input input-sm input-bordered flex items-center gap-2">
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
                </label> -->

                <form method="GET" action="">
                <select name="grade_level" class="select select-bordered select-sm w-full max-w-xs" onchange="this.form.submit()">
                    <option disabled selected>Select Grade level</option>
                    <option>Grade 12</option>
                    <option>Grade 11</option>
                    <option>Grade 10</option>
                    <option>Grade 9</option>
                    <option>Grade 8</option>
                    <option>Grade 7</option>
                    <option>Grade 6</option>
                    <option>Grade 5</option>
                    <option>Grade 4</option>
                    <option>Grade 3</option>
                    <option>Grade 2</option>
                    <option>Grade 1</option>
                </select>
                </form>
            </div>

      
        </div>

        <div class="p-7 bg-white rounded-md space-y-6 mt-7">
                <!-- 
                <div class="border border-gray-100 p-4 space-y-2">
                        <p class="text-xl font-semibold">Grade 1 - First Quarter</p>
                        <p>A.Y. 2023-2024</p>
                        <div class="flex items-center justify-between">
                            <p class="px-3 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm">Section A</p>
                        
                            <div class="tooltip" data-tip="Download Grade">
                                <button class="btn btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                </div> -->
        <div class="grid grid-cols-2 gap-2">
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

                            // Display grades for each subject in different quarters
                            foreach ($subjects as $subject => $subject_grades) {
                                echo "<tr>";
                                echo "<td class='border border-gray-300 px-4 py-2 text-left'>" . htmlspecialchars($subject) . "</td>";

                                // Initialize variables for each quarter
                                $quarter_1 = $quarter_2 = $quarter_3 = $quarter_4 = '';

                                // Iterate over the grades to assign them to the appropriate quarter
                                foreach ($subject_grades as $grade) {
                                    // Round the grade to the nearest whole number
                                    $roundedGrade = round($grade['grade']);

                                    // Check the Quarter value and assign the grade accordingly
                                    switch ($grade['Quarter']) {
                                        case '1st':
                                            $quarter_1 = $roundedGrade;
                                            break;
                                        case '2nd':
                                            $quarter_2 = $roundedGrade;
                                            break;
                                        case '3rd':
                                            $quarter_3 = $roundedGrade;
                                            break;
                                        case '4th':
                                            $quarter_4 = $roundedGrade;
                                            break;
                                    }
                                }

                                // Output the grades for each quarter
                                echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_1) . "</td>";
                                echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_2) . "</td>";
                                echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_3) . "</td>";
                                echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_4) . "</td>";
                                echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_4) . "</td>"; // Final Grade
                                echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($quarter_4) . "</td>"; // Remarks
                                echo "</tr>";
                            }

                        
                        ?>
                         <tr>
                            <td class="border border-gray-300 px-4 py-2 text-left font-bold">General Average</td>
                            <td colspan="4" class="border border-gray-300 px-4 py-2"></td>
                            <td class="border border-gray-300 px-4 py-2"></td>
                            <td class="border border-gray-300 px-4 py-2"></td>
                        </tr>
                    </tbody>
                </table>
        </div>





                
               

        </div>



    </div>

    
</body>
</html>