<?php
include '../../config/db.php';
session_start();

// Fetch the grade level from the URL
if (isset($_GET['gradelevel'])) {
    $gradeLevel = $_GET['gradelevel'];
    // echo "Grade Level: " . $gradeLevel;  // Debugging line
} else {
    // echo "Grade level not set.";  // Debugging line
}

// Fetch sections for the dropdown and filter by grade level
$gradeLevelFilter = "";
if (isset($_GET['gradelevel'])) {
    $gradeLevelFilter = "WHERE grade_level = '" . $_GET['gradelevel'] . "'";
}

$sql = "SELECT section_name, grade_level FROM sections $gradeLevelFilter";
$result = $connection->query($sql);
$sections = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}

// Fetch details for a selected section
$sectionDetails = null;
if (isset($_GET['section'])) {
    $section = $_GET['section'];

    // Query to fetch the section details, adviser, and students
    $sql = "
        SELECT s.section_name, s.grade_level, CONCAT(t.First_Name, ' ', t.Last_Name) AS adviser, 
            se.student_id, st.first_name, st.last_name, st.gender
        FROM sections s
        JOIN teachers t ON s.adviser_id = t.teacher_id
        LEFT JOIN student_enrollment se ON se.section = s.section_id
        LEFT JOIN students st ON se.student_id = st.student_id
        WHERE s.section_name = '$section'
        ";

    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $students = [];
        $maleCount = 0;
        $femaleCount = 0;
        $maleStudents = [];
        $femaleStudents = [];

        // Get section and adviser details from the first row
        $row = $result->fetch_assoc();
        $sectionDetails['section_name'] = $row['section_name'];
        $sectionDetails['grade_level'] = $row['grade_level'];
        $sectionDetails['adviser'] = $row['adviser'];

        // Rewind the result set to fetch students again
        $result->data_seek(0);

        // Categorize students by gender
        while ($row = $result->fetch_assoc()) {
            if (isset($row['gender']) && $row['gender'] !== NULL) {
                // Add student details to the array
                $students[] = array(
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'gender' => $row['gender']
                );

                // Count male and female students
                if ($row['gender'] == 'male') {
                    $maleCount++;
                    $maleStudents[] = $row['first_name'] . " " . $row['last_name'];
                } elseif ($row['gender'] == 'female') {
                    $femaleCount++;
                    $femaleStudents[] = $row['first_name'] . " " . $row['last_name'];
                }
            }
        }

        $sectionDetails['students'] = $students;
    }

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

$connection->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">


    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>


    <html data-theme="light">

    </html>

</head>

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">

        <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">

            <div class="flex justify-between items-center flex-wrap gap-6">

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="class_section.php">Class List</a></li>
                        <li>View Sections</li>
                    </ul>
                </div>


                <form id="sectionForm" action="add_schedule.php" method="GET" onsubmit="return validateSelection()">
                    <!-- Hidden inputs for gradelevel and section -->
                    <input type="hidden" name="gradelevel" id="gradelevel" value="<?php echo isset($_GET['gradelevel']) ? $_GET['gradelevel'] : ''; ?>">
                    <input type="hidden" name="section" id="section" value="<?php echo isset($_GET['section']) ? $_GET['section'] : ''; ?>">

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Schedule
                    </button>

                </form>

            </div>

            <div class="relative flex items-center w-full mt-7">
                <select name="sectionselect" id="sectionselect" required class="select select-bordered w-full">
                    <option value="" disabled <?php echo !isset($_GET['section']) ? 'selected' : ''; ?>>Select Section</option>
                    <?php
                    foreach ($sections as $section) {
                        // Check if this section is selected (based on the URL parameter)
                        $selected = (isset($_GET['section']) && $_GET['section'] == $section['section_name']) ? 'selected' : '';
                        echo "<option value='" . $section['section_name'] . "' $selected data-gradelevel='" . $section['grade_level'] . "'>" . $section['section_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>



            <div class="border border-gray-300 rounded bg-white mt-3.5">

                <div class="p-5 bg-blue-50 rounded-t flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <img src="../../assets/images/smeclogo.png" alt="" class="w-12 h-12 object-cover">
                        <div>
                            <h1 class="font-semibold ">Sta. Marta Educational Center Inc.</h1>
                            <p class="text-xs text-gray-500">Dolmar Subd., Kalawaan Pasig City</p>

                        </div>

                    </div>


                    <div>

                        <div class="tooltip" data-tip="Download">
                            <button class="btn btn-sm btn-ghost"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </button>
                        </div>

                        <div class="tooltip" data-tip="Archive">
                            <button class="btn btn-sm btn-ghost"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-blue-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

                <div id="section-details" class="py-7 flex flex-col justify-center items-center gap-5">
                    <?php
                    if ($sectionDetails) {
                        // Display Section Information (adviser and students)
                        echo "<h1 class='text-3xl font-bold text-center capitalize'>" . $sectionDetails['grade_level'] . " - " . $sectionDetails['section_name'] . "</h1>";
                        echo "<p class='text-sm text-center italic'>Adviser: " . $sectionDetails['adviser'] . "</p>";

                        // Initialize gender counts
                        $maleCount = 0;
                        $femaleCount = 0;
                        $maleStudents = [];
                        $femaleStudents = [];

                        // Categorize students by gender
                        foreach ($sectionDetails['students'] as $student) {
                            if ($student['gender'] == 'male') {
                                $maleCount++;
                                $maleStudents[] = $student['first_name'] . " " . $student['last_name'];
                            } else {
                                $femaleCount++;
                                $femaleStudents[] = $student['first_name'] . " " . $student['last_name'];
                            }
                        }
                    ?>
                        <div class="flex gap-[180px] mt-5">
                            <div>
                                <h1 class="text-lg font-medium mb-1">Boys</h1>
                                <p>Count: <?php echo $maleCount; ?></p>
                                <ul class="list-decimal">
                                    <?php foreach ($maleStudents as $male) { ?>
                                        <li><?php echo $male; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <div>
                                <h1 class="text-lg font-medium mb-1">Girls</h1>
                                <p>Count: <?php echo $femaleCount; ?></p>
                                <ul class="list-decimal">
                                    <?php foreach ($femaleStudents as $female) { ?>
                                        <li><?php echo $female; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>



            <div class="border border-gray-300 rounded bg-white mt-3.5">
                <h1 class="text-xl font-semibold text-center p-5 bg-blue-50 rounded-t text-blue-600">Class Schedule</h1>

                <div class="overflow-hidden p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
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





    </div>

</body>

</html>

<script>
    // Function to handle the validation
    function validateSelection() {
        const sectionSelect = document.getElementById('sectionselect');
        if (sectionSelect.value === "") {
            alert('Please select a section');
            return false; // Prevent form submission if no section is selected
        }
        return true; // Allow form submission if a section is selected
    }
</script>
<script>
    document.getElementById('sectionselect').addEventListener('change', function() {
        const sectionName = this.value;
        const gradeLevel = this.selectedOptions[0].getAttribute('data-gradelevel'); // Get grade level

        window.location.href = "?section=" + sectionName + "&gradelevel=" + gradeLevel; // Append both parameters to the URL
    });
</script>

<!-- <script>
    // Handle the selection of section and fetch the details dynamically
    document.getElementById('sectionselect').addEventListener('change', function() {
        const sectionName = this.value;
        window.location.href = "?section=" + sectionName;
    });
</script> -->


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