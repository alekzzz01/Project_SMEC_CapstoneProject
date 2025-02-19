<?php 
session_start();
include '../../config/db.php';

// Fetching available tracks
$tracks = ["Elementary", "Highschool", "ABM", "GAS", "HUMSS"];

// Insert new subject if form is submitted
$notificationMessage = ''; // Variable to store success/error message
if (isset($_POST['submitForm'])) {
    // Get form values
    $subjectName = $_POST['subject_name'];
    $subjectCode = $_POST['subject_code'];
    $description = $_POST['description'];
    $gradeLevel = $_POST['gradelevel'];
    $track = $_POST['track'];
    $totalHours = $_POST['total_hours'];
   
    $formattedGradeLevel = strtolower(str_replace("Grade-", "grade-", $gradeLevel));

    // Generate subject_id based on grade level and auto-increment number
    $selectSql = "SELECT MAX(CAST(SUBSTRING(subject_id, LENGTH('$formattedGradeLevel') + 2) AS UNSIGNED)) AS last_id 
                  FROM subjects 
                  WHERE subject_id LIKE '$formattedGradeLevel-%'";
    $result = $connection->query($selectSql);
    $lastId = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['last_id'];
    }
    
    // Increment the last_id to create a new subject_id
    $newSubjectId = $formattedGradeLevel . '-' . ($lastId + 1);

    // Prepare the SQL to insert data into the subjects table
    $insertSql = "INSERT INTO subjects (subject_id, subject_name, subject_code, description, created_at, grade_level, track, total_hours) 
                  VALUES ('$newSubjectId', '$subjectName', '$subjectCode', '$description', NOW(), '$gradeLevel', '$track', '$totalHours')";

    if ($connection->query($insertSql) === TRUE) {
        $notificationMessage = 'New subject added successfully!';
    } else {
        $notificationMessage = 'Error: ' . $insertSql . "<br>" . $connection->error;
    }
}

// Get the selected track from the filter form
$trackFilter = isset($_GET['track']) ? $_GET['track'] : '';

// Fetch subjects based on the selected track
$sql = "SELECT subject_id, subject_name, subject_code, description, grade_level, track, created_at FROM subjects";

// Add a WHERE clause if a track is selected
if ($trackFilter) {
    $sql .= " WHERE track = '$trackFilter'";
}

$result = $connection->query($sql);
$subjects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>

    <link rel="stylesheet" href="../../assets/css/styles.css">
     
     <script src="../../assets/js/script.js"></script>
 
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3.4.0/notyf.min.css">
    
     <script src="https://cdn.jsdelivr.net/npm/notyf@3.4.0/notyf.min.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
  
     <script src="https://cdn.tailwindcss.com"></script>
 
     <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
     <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
 
     <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">
    
  
     <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
 
      
     <html data-theme="light"></html>


    <!-- DataTables CSS (Hover Styling) -->
    <link href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" rel="stylesheet">
  
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>


</head>
<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

    <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">

          

            <div class="border border-gray-300 rounded bg-white">

                <h1 class="font-semibold p-5 bg-blue-50 rounded-t text-blue-600">Add Subject</h1>

                <form action="" method="POST" class="p-5 space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Subject Name</label>
                            <div class="relative flex items-center">
                            <input name="subject_name" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="Enter Subject Name"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Subject Code</label>
                            <div class="relative flex items-center">
                            <input name="subject_code" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="Enter Subject Code"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Grade Level</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Grade Level</option>
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
                        

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Track/Strand</label>
                            <select name="track" id="track" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Track/Strand</option>
                                <?php
                                foreach ($tracks as $track) {
                                    echo "<option value='$track'>$track</option>";
                                }
                                ?>
                               
        
                            </select>
                        </div>


                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Total Number of Hours Per Week</label>
                            <div class="relative flex items-center">
                            <input name="total_hours" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="8"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Description</label>
                            <div class="relative flex items-center">
                            <input name="description" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="Enter subject description"/>
                            </div>
                                       
                        </div>

                        </div>
                    
                        <div class=" flex items-center justify-center">
                            <button type="submit" name="submitForm" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit</button>
                        </div>
                        

                        

                </form>


            </div>

            <div>
                    <h1 class="text-lg font-medium mb-1 mt-7">View All Subjects</h1>
            </div>

              <!-- Filter Form for Track -->
              <div class="border border-gray-300 rounded bg-white mt-3.5 p-6">
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-2 block">Filter Track</label>
                    <form action="" method="GET">
                        <select name="track" id="track" required class="select select-bordered w-full bg-gray-50">
                            <option value="" disabled selected>Select Track</option>
                            <?php
                                foreach ($tracks as $track) {
                                    echo "<option value='$track'>$track</option>";
                                }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                    </form>
                </div>
            </div>


            <div class="border border-gray-300 rounded bg-white mt-3.5 p-6">

            <table id="example" class="min-w-full divide-y divide-gray-200">
                <thead class="border border-gray-300 text-sm">
                    <tr>
                        <th class="py-3 px-4 text-left">Subject ID</th>
                        <th class="py-3 px-4 text-left">Subject Name</th>
                        <th class="py-3 px-4 text-left">Subject Code</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-left">Grade Level</th> <!-- New column -->
                        <th class="py-3 px-4 text-left">Track</th> <!-- New column -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 border border-gray-300">
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($subject['subject_id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($subject['subject_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($subject['subject_code']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($subject['description']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($subject['grade_level']) ?></td> <!-- Display grade level -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($subject['track']) ?></td> <!-- Display track -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            </div>


        </div>


    </div>
                 
    <script>
        $(document).ready(function () {
        var table = $('#example').DataTable({
            searching: true,  // Enables the search box
            paging: true,     // Enables pagination
            ordering: true,   // Enables column sorting
            info: true,       // Displays table information
        });

        // Show Notyf success notification if subject is added
        <?php if ($notificationMessage): ?>
            const notyf = new Notyf({
                position: {
                    x: 'right',  // Horizontal position (right)
                    y: 'top'     // Vertical position (top)
                },
                duration: 3000, // Set duration for how long the notification shows (in ms)
                ripple: true    // Optional: adds a ripple effect when the notification appears
            });
            notyf.success("<?= $notificationMessage ?>");
        <?php endif; ?>
    });
    </script>
    
</body>
</html>

