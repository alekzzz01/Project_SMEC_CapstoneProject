<?php 
session_start();
include '../../config/db.php';

// Fetch the open school year from the database
$schoolYearQuery = "SELECT school_year FROM school_year WHERE status = 'open' LIMIT 1";
$schoolYearResult = $connection->query($schoolYearQuery);
$schoolYear = '';
if ($schoolYearResult->num_rows > 0) {
    $schoolYearRow = $schoolYearResult->fetch_assoc();
    $schoolYear = $schoolYearRow['school_year']; // Fetch the open school year
}

// Fetch teachers for the adviser dropdown
$teacherSql = "SELECT teacher_id, CONCAT(First_Name, ' ', Last_Name) AS teacher_name FROM teachers";
$teacherResult = $connection->query($teacherSql);
$teachers = [];
if ($teacherResult->num_rows > 0) {
    while ($row = $teacherResult->fetch_assoc()) {
        $teachers[] = $row; // Add each teacher to the array
    }
}

// Fetching available tracks (you can update this based on your own logic)
$tracks = ["Elementary", "Highschool", "ABM", "GAS", "HUMSS"];

// Get the sort order from the dropdown if it's set
$sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; // Default to ascending if not set

// Fetch sections for the dropdown (this can be reused from view_sections.php)
$query = "SELECT s.grade_level, sy.school_year, COUNT(s.section_name) AS total_sections
          FROM sections s
          JOIN school_year sy ON s.school_year_id = sy.school_year_id
          GROUP BY s.grade_level, sy.school_year
          ORDER BY s.grade_level $sortOrder";  // Sorting based on grade_level

$result = $connection->query($query);

// Store results in an array
$sections = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row; // Add each row to the array
    }
}

if (isset($_POST['createSection'])) {
    // Get form values
    $gradeLevel = $_POST['gradelevel']; // This will be "Grade-1", "Grade-2", etc.

    // Convert "Grade-1" to "grade-1"
    $formattedGradeLevel = strtolower(str_replace("Grade-", "grade-", $gradeLevel));

    // Get the school year ID from the form
    $schoolYearId = $_POST['school_year_id'];  // Use school_year_id from the form (not the school_year)

    // Continue if school year ID is provided
    if ($schoolYearId) {
        // Get other form values
        $sectionName = $_POST['section_name'];
        $track = $_POST['track'];
        $adviserId = $_POST['adviser_id'];  // This is the adviser_id now
        $numStudents = $_POST['num_students'];

        // Insert the data into the database (use school_year_id instead of school_year)
        $insertSql = "
            INSERT INTO sections (section_name, grade_level, track, adviser_id, num_students, school_year_id)
            VALUES ('$sectionName', '$formattedGradeLevel', '$track', '$adviserId', '$numStudents', '$schoolYearId')
        ";

        if ($connection->query($insertSql) === TRUE) {
            echo "New section added successfully!";
        } else {
            echo "Error: " . $insertSql . "<br>" . $connection->error;
        }
    } else {
        echo "No open school year found.";
    }

    // Redirect or take further actions
    header("Location: class_section.php");
    exit();
}


$connection->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section List</title>

    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
 
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">

 
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

     
    <html data-theme="light"></html>
    



</head>
<body class="flex min-h-screen">
    

<?php include('./components/sidebar.php'); ?>


<div class="flex flex-col w-full">

<?php include('./components/navbar.php'); ?>


    <div class="p-6 bg-[#f2f5f8] h-full">

   
        <div class="flex items-center justify-between flex-wrap gap-6">

            
            <div>
                    <h1 class="text-lg font-medium mb-1">Section</h1>
                
            </div>

      
            <div class="flex items-center justify-between gap-1 lg:gap-3 flex-wrap">
                
                    <select name="sort_order" id="sortOrder" class="select select-bordered select-sm">
                        <option value="ASC" <?php echo isset($_GET['sort_order']) && $_GET['sort_order'] == 'ASC' ? 'selected' : ''; ?>>Sort by Year (Ascending)</option>
                        <option value="DESC" <?php echo isset($_GET['sort_order']) && $_GET['sort_order'] == 'DESC' ? 'selected' : ''; ?>>Sort by Year (Descending)</option>
                    </select>


                <div class="border border-r h-6"></div>

                <button onclick="add_section.showModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Add Section
                </button>

            
                <!-- 
                <button class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>

                    Archive
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>

                    Delete
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>

                    Restore
                </button> -->

            
            </div>

        </div>

        <div class="my-7 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php 
                if (!empty($sections)) {
                    // Loop through the sections and display them
                    foreach ($sections as $row) {
                        // Extract the numeric part of the grade_level (e.g., "Grade-11" becomes "11")
                        $gradeLevel = explode('-', $row['grade_level'])[1]; // This will return the number part of Grade-11, Grade-12, etc.

                        echo '<div class="p-6 bg-white rounded-t-md shadow border-b-4 border-green-600">';
                        echo '<p class="font-bold text-lg mb-1">Grade: ' . htmlspecialchars($gradeLevel, ENT_QUOTES, 'UTF-8') . '</p>';  // Display only the number
                        echo '<p class="text-base-content/70 text-sm font-medium mb-6">A.Y. ' . htmlspecialchars($row['school_year'], ENT_QUOTES, 'UTF-8') . '</p>';
                        echo '<div class="flex items-center justify-between">';
                        echo '<p class="px-3 py-1.5 rounded-full hover:bg-gray-50 border-2 border-gray-300 text-base-content/70 font-medium text-sm transition-colors inline-flex items-center gap-1.5"><span class="font-semibold text-lg">' . htmlspecialchars($row['total_sections'], ENT_QUOTES, 'UTF-8') . '</span> Total Sections</p>';
                        echo '<a href="view_sections.php?gradelevel=' . urlencode($row['grade_level']) . '" class="btn "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg></a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No sections found</p>";
                }
            ?>
            </div>




    </div>




</div>



<dialog id="add_section" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add new section</h3>

            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <form action="" class="py-4 grid grid-cols-2 gap-6" method="POST">

            <div>
                    <label class="text-gray-800 text-sm mb-2 block">Grade Level</label>
                    <div class="relative flex items-center">
                        <select name="gradelevel" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                            <option value="" disabled selected>Choose Grade Level</option>
                            <option value="Nursery">Nursery</option>
                            <option value="Kinder">Kinder</option>
                            <option value="Grade-1">Grade 1</option>
                            <option value="Grade-2">Grade 2</option>
                            <option value="Grade-3">Grade 3</option>
                            <option value="Grade-4">Grade 4</option>
                            <option value="Grade-5">Grade 5</option>
                            <option value="Grade-6">Grade 6</option>
                            <option value="Grade-7">Grade 7</option>
                            <option value="Grade-8">Grade 8</option>
                            <option value="Grade-9">Grade 9</option>
                            <option value="Grade-10">Grade 10</option>
                            <option value="Grade-11">Grade 11</option>
                            <option value="Grade-12">Grade 12</option>
                        </select>
                    </div>
                </div>

                    
        
                    <div id="section_name_field" >
                            <label class="text-gray-800 text-sm mb-2 block">Section Name</label>
                            <div class="relative flex items-center">
                            <input name="section_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Section Name" />
                        
                            </div>
                    </div>

                    <div>
                <label class="text-gray-800 text-sm mb-2 block">Track/Strand</label>
                <div class="relative flex items-center">
                    <select name="track" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                        <option value="" disabled selected>Choose Track/Strand</option>
                        <?php
                        foreach ($tracks as $track) {
                            echo "<option value='$track'>$track</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div>
                <label class="text-gray-800 text-sm mb-2 block">Academic Year</label>
                <div class="relative flex items-center">
                    <select name="school_year_id" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                        <option value="" disabled selected>Select School Year</option>
                        <!-- Populate the open academic year -->
                        <?php
                        // Fetch the open school year
                        $schoolYearQuery = "SELECT school_year_id, school_year FROM school_year WHERE status = 'open' LIMIT 1";
                        $schoolYearResult = $connection->query($schoolYearQuery);

                        if ($schoolYearResult->num_rows > 0) {
                            $schoolYearRow = $schoolYearResult->fetch_assoc();
                            // Display the open academic year and its id
                            echo "<option value='" . $schoolYearRow['school_year_id'] . "'>" . $schoolYearRow['school_year'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>



                    
                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Class Adviser</label>
                        <select name="adviser_id" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                            <option value="" disabled selected>Assign Adviser</option>
                            <?php
                            foreach ($teachers as $teacher) {
                                echo "<option value='" . $teacher['teacher_id'] . "'>" . htmlspecialchars($teacher['teacher_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Number of Students</label>
                        <div class="relative flex items-center">
                            <input name="num_students" type="number" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Number of Students" />
                        
                        </div>
                    </div>

                    <div class="modal-action col-span-2">
                        <button type="submit" name="createSection" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Save Section</button>
                    </div>
     

            </form>

        </div>
           
          
</dialog>

<script>
        // Handle sorting when dropdown value changes
        document.getElementById('sortOrder').addEventListener('change', function () {
            const sortOrder = this.value;
            window.location.search = `?sort_order=${sortOrder}`;
        });
    </script>


</body>
</html>