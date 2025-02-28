<?php
session_start();
include '../../config/db.php';

// Get teacher_id from URL
$teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '';

$section_info = [];
$advisory_query = "SELECT s.section_id, s.section_name, s.grade_level, s.track, s.num_students, 
                         t.First_Name, t.Last_Name
                  FROM sections s
                  JOIN teachers t ON s.adviser_id = t.teacher_id
                  WHERE s.adviser_id = ?";

$stmt = $connection->prepare($advisory_query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $section_info = $result->fetch_assoc();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisory Class</title>



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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

</head>

<body class="min-h-screen bg-[#f2f5f8]">

    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12 h-full ">



    <?php if (!empty($section_info)): ?>
            <div class="rounded bg-teal-100 p-4 mb-7 space-y-2 shadow">
                <h2 class="text-teal-900 font-semibold text-xl">Advisory Class</h2>
                <h1 class="font-extrabold text-4xl text-teal-900">
                    Grade: <?php echo htmlspecialchars($section_info['grade_level']); ?> - 
                    <?php echo htmlspecialchars($section_info['section_name']); ?>
                </h1>
                <p class="text-teal-800 text-sm italic">
                    Adviser: <?php echo htmlspecialchars($section_info['First_Name'] . ' ' . $section_info['Last_Name']); ?>
                </p>
                <?php if (!empty($section_info['track'])): ?>
                    <p class="text-teal-800 text-sm">Track: <?php echo htmlspecialchars($section_info['track']); ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col">
                <div class="overflow-hidden p-4 rounded border border-gray-200 bg-white">
                    <table id="example" class="min-w-full divide-y divide-gray-200">
                        <thead class="border border-gray-300 text-sm">
                            <tr>
                                <th>Student No.</th>
                                <th>Student Name</th>
                                <th>Birth Date</th>
                                <th>Gender</th>
                                <th>Contact Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get students in this section
                            $students_query = "SELECT s.student_id, s.student_number, s.first_name, s.last_name, s.date_of_birth, 
                           s.gender, s.contact_number
                   FROM students s
                   JOIN student_enrollment se ON s.student_id = se.student_id
                   JOIN sections sec ON se.section = sec.section_id
                   WHERE sec.section_id = ?";
                            
                            $stmt = $connection->prepare($students_query);
                            $stmt->bind_param("i", $section_info['section_id']);
                            $stmt->execute();
                            $students_result = $stmt->get_result();
                            
                            while ($student = $students_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($student['student_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['date_of_birth']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['gender']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['contact_number']) . "</td>";
                                echo "<td>
                                     <a href='view_Student.php?student_id=" . htmlspecialchars($student['student_id']) . "' class='text-blue-500 hover:text-blue-700'>View</a>
                                      </td>";
                                echo "</tr>";
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span>No advisory class found for this teacher.</span>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            searching: true, // Enables the search box
            paging: true, // Enables pagination
            ordering: true, // Enables column sorting
            info: true // Displays table information (e.g., "Showing 1 to 10 of 50 entries")
        });
    });
</script>