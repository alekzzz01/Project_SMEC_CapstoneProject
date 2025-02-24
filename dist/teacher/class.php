<?php
session_start();
include '../../config/db.php'; // Include your database connection file

// Assuming user_id is stored in session after login
$teacher_id = $_SESSION['user_id'];

// Fetch the teacher_id associated with the logged-in user_id
$query = "SELECT teacher_id FROM teachers WHERE user_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $teacher_id); // Using user_id here
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("⚠️ Error: No teacher found for user_id = " . $teacher_id);
}

$teacher_id = $row['teacher_id']; 

// Fetch sections where the logged-in teacher is the adviser
$query = "SELECT sec.section_id, sec.school_year_id, sec.grade_level, sec.section_name, 
       sec.track, sec.adviser_id, sec.num_students, 
       sub.subject_name  
FROM sections sec 
LEFT JOIN schedules sch ON sec.section_name = sch.section  -- Match section_name
LEFT JOIN subjects sub ON sch.subject_id = sub.subject_id
WHERE sec.adviser_id = ?";



$stmt = $connection->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class</title>


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

        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                    </svg></div>
                <div>
                    <p class="font-bold">📢 Announcement</p>
                    <p class="text-sm">Encoding of grades for this semester is now open! Select a class below to start entering grades.</p>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-between">
            <h4 class="text-2xl font-medium mb-1">Class</h4>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Sort by Subject</option>
                        <option value="asc">A-Z (Alphabetical)</option>
                        <option value="desc">Z-A (Reverse Alphabetical)</option>
                    </select>

                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Sort by Section</option>
                        <option value="asc">A-Z (Alphabetical)</option>
                        <option value="desc">Z-A (Reverse Alphabetical)</option>
                    </select>
                </div>

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

            <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">

                <thead>
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Class</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Students</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Subject</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Section</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($row['section_name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($row['num_students']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($row['subject_name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($row['section_name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                <a href="encodeGrades.php?section=<?= htmlspecialchars($row['section_name']); ?>" class="text-teal-700 hover:underline">Enter Grades</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>


        </div>





    </div>

</body>

</html>

<?php
$stmt->close();
$connection->close();
?>