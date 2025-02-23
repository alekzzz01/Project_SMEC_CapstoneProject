<?php
include '../../config/db.php';

if (isset($_GET['gradelevel'])) {
    $gradeLevel = isset($_POST['gradelevel']) ? $_POST['gradelevel'] : (isset($_GET['gradelevel']) ? $_GET['gradelevel'] : '');
}

// Fetch sections for the dropdown (this can be reused from view_sections.php)
$sql = "SELECT section_name, grade_level FROM sections";
$result = $connection->query($sql);
$sections = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}

// Fetch teachers for the teacher dropdown
$teacherSql = "SELECT teacher_id, CONCAT(First_Name, ' ', Last_Name) AS teacher_name FROM teachers";
$teacherResult = $connection->query($teacherSql);
$teachers = [];
if ($teacherResult->num_rows > 0) {
    while ($row = $teacherResult->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Fetch subjects for the subject dropdown
$subjectSql = "SELECT subject_id, subject_name FROM subjects";
$subjectResult = $connection->query($subjectSql);
$subjects = [];
if ($subjectResult->num_rows > 0) {
    while ($row = $subjectResult->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Fetch details for a selected section (using sectionselect from the URL)
$sectionDetails = null;
if (isset($_GET['sectionselect'])) {
    $section = $_GET['sectionselect'];  // This is where we fetch the section from the URL
    
    // Schedule query to fetch class schedule for the selected section
    $scheduleSql = "
        SELECT sub.subject_code, sub.subject_name, sc.time_in, sc.time_out, CONCAT(t.First_Name, ' ', t.Last_Name) AS teacher
        FROM schedules sc
        JOIN subjects sub ON sc.subject_id = sub.subject_id
        JOIN teachers t ON sc.teacher_id = t.teacher_id
        WHERE sc.section = '$section'
        ";

        $scheduleResult = $connection->query($scheduleSql);

        if ($scheduleResult->num_rows > 0) {
            $scheduleDetails = [];
            while ($scheduleRow = $scheduleResult->fetch_assoc()) {
                $scheduleDetails[] = array(
                    'subject_code' => $scheduleRow['subject_code'],
                    'subject_name' => $scheduleRow['subject_name'],
                    'time_in' => $scheduleRow['time_in'],
                    'time_out' => $scheduleRow['time_out'],
                    'teacher' => $scheduleRow['teacher']
                );
            }
            $sectionDetails['schedule'] = $scheduleDetails;
        }
}

if (isset($_POST['submitForm'])) {
    // Get form values
    $section = $_POST['section'];
    $subject = $_POST['subject'];
    $time_in = $_POST['start_time']; // Use time_in instead of start_time
    $time_out = $_POST['end_time']; // Use time_out instead of end_time
    $teacher = $_POST['teacher'];
    $gradeLevel = $_GET['gradelevel'];  // Get grade level from URL

    // Get the open school year
    $schoolYearQuery = "SELECT school_year FROM school_year WHERE status = 'Open' LIMIT 1";
    $schoolYearResult = $connection->query($schoolYearQuery);
    if ($schoolYearResult->num_rows > 0) {
        $schoolYearRow = $schoolYearResult->fetch_assoc();
        $schoolYear = $schoolYearRow['school_year'];
    } else {
        // Handle the case where there is no open school year
        echo "No open school year found.";
        exit;
    }

    $gradeLevel = strtolower($_GET['gradelevel']); // Convert to lowercase

    if ($gradeLevel === "kinder") {
        $schedulePrefix = "KSched";
    } elseif ($gradeLevel === "preparatory") {
        $schedulePrefix = "PSched";
    } elseif ($gradeLevel === "nursery") {
        $schedulePrefix = "NSched";
    } elseif (is_numeric($gradeLevel) && $gradeLevel >= 1 && $gradeLevel <= 12) {
        $schedulePrefix = "G" . $gradeLevel . "Sched";
    } else {
        echo "Invalid grade level.";
        exit;
    }

    // Get the last schedule code with the same prefix
    $scheduleCodeQuery = "SELECT schedule_code FROM schedules WHERE schedule_code LIKE '$schedulePrefix%' ORDER BY schedule_code DESC LIMIT 1";
    $scheduleCodeResult = $connection->query($scheduleCodeQuery);
    
    if ($scheduleCodeResult->num_rows > 0) {
        $row = $scheduleCodeResult->fetch_assoc();
        preg_match('/\d+$/', $row['schedule_code'], $matches);
        $nextNumber = $matches ? intval($matches[0]) + 1 : 1;
    } else {
        $nextNumber = 1;
    }

    // Generate new schedule code
    $newScheduleCode = $schedulePrefix . $nextNumber;

    // Insert the new schedule
    $insertSql = "
        INSERT INTO schedules (schedule_code, section, subject_id, time_in, time_out, teacher_id, grade_level, school_year) 
        VALUES ('$newScheduleCode', '$section', '$subject', '$time_in', '$time_out', '$teacher', '$gradeLevel', '$schoolYear')
    ";

    if ($connection->query($insertSql) === TRUE) {
        header("Location: add_schedule.php?sectionselect=$section&gradelevel=$gradeLevel&status=approved");
        exit();
    } else {
        echo "Error: " . $connection->error;
    }
}

$connection->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>

    <link rel="stylesheet" href="../../assets/css/styles.css">
     
     <script src="../../assets/js/script.js"></script>
 
  
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
  
     <script src="https://cdn.tailwindcss.com"></script>
 
     <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
     <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
 
     <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">
 
     <link href="https://cdn.jsdelivr.net/npm/notyf@3.1.0/notyf.min.css" rel="stylesheet">

     <script src="https://cdn.jsdelivr.net/npm/notyf@3.1.0/notyf.min.js"></script>
  
     <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
 
      
     <html data-theme="light"></html>



</head>
<body class="flex min-h-screen">


    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

    <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">


            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="class_section.php">Class List</a></li>
                    <li><a href="view_sections.php">View Sections</a></li>
                    <li>Add Schedule</li>
                </ul>
            </div>


            <div class="border border-gray-300 rounded bg-white mt-7">
                <h1 class="font-semibold p-5 bg-blue-50 rounded-t text-blue-600">Add New Schedule</h1>

                <form class="p-5 space-y-6" method="POST">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Section Name</label>
                            <select name="section" id="section" required class="select select-bordered w-full bg-gray-50">
                                <option value="" disabled selected>Select Section</option>
                                <?php
                                foreach ($sections as $section) {
                                    echo "<option value='" . $section['section_name'] . "'>" . $section['section_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Subject</label>
                            <select name="subject" id="subject" required class="select select-bordered w-full bg-gray-50">
                                <option value="" disabled selected>Select Subject</option>
                                <?php
                                foreach ($subjects as $subject) {
                                    echo "<option value='" . $subject['subject_id'] . "'>" . $subject['subject_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Set Start Time</label>
                            <input name="start_time" type="time" class="bg-gray-50 w-full text-gray-800 input input-bordered" required />
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Set End Time</label>
                            <input name="end_time" type="time" class="bg-gray-50 w-full text-gray-800 input input-bordered" required />
                        </div>

                        <div class="relative flex items-center w-full mt-7">
                            <select name="teacher" id="teacher" required class="select select-bordered w-full">
                                <option value="" disabled selected>Select Teacher</option>
                                <?php
                                // Loop through the teachers and populate the dropdown
                                foreach ($teachers as $teacher) {
                                    // Display each teacher's name as an option in the dropdown
                                    echo "<option value='" . $teacher['teacher_id'] . "'>" . $teacher['teacher_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                          
                    <input type="hidden" name="gradelevel" value="<?php echo htmlspecialchars($gradeLevel); ?>">

                    <div class="flex items-center justify-end">
                        <button type="submit" name="submitForm" class="py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Add Schedule</button>
                    </div>
                </form>
            </div>

                
            <div class="border border-gray-300 rounded bg-white mt-3.5">
            <h1 class="text-xl font-semibold text-center p-5 bg-blue-50 rounded-t text-blue-600">Class Schedule</h1>
            <div class="overflow-hidden p-5">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Teacher</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        if (isset($sectionDetails['schedule'])) {
                            foreach ($sectionDetails['schedule'] as $schedule) {
                                echo "<tr>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800'>{$schedule['subject_code']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800'>{$schedule['subject_name']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800'>{$schedule['time_in']} - {$schedule['time_out']}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800'>{$schedule['teacher']}</td>
                                    </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

                        




        </div>




    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for `status` query parameter in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            // Display notifications based on `status`
            if (status === 'approved') {
                const notyf = new Notyf({
                    duration: 3000, // Duration of the notification (3 seconds)
                    position: {
                        x: 'right', // Align notifications to the right
                        y: 'top'     // Show notifications at the top
                    }
                });
                notyf.success('New schedule added successfully!');

                // Remove the `status` parameter from the URL
                urlParams.delete('status');
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`);
            }
        });
    </script>
</body>
</html>

<script>

$(document).ready(function() {
  $('#toggleSidebar').on('click', function() {
      $('#sidebar').toggleClass('-translate-x-full');
  });

   $('#closeSidebar').on('click', function() {
      $('#sidebar').addClass('-translate-x-full');
  });


  
});

</script>