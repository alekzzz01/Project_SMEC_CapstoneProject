<?php
session_start();
include '../../config/db.php';

// Get student_id from URL
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Get teacher_id from URL
$teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '';

// Get student information
$student_info = [];
$student_query = "SELECT s.*, se.section, sec.section_name, sec.grade_level, 
                        t.First_Name as teacher_fname, t.Last_Name as teacher_lname,
                        sy.school_year
                 FROM students s
                 JOIN student_enrollment se ON s.student_id = se.student_id
                 JOIN sections sec ON se.section = sec.section_id
                 JOIN teachers t ON sec.adviser_id = t.teacher_id
                 JOIN school_year sy ON se.school_year_id = sy.school_year_id
                 WHERE s.student_id = ?";

$stmt = $connection->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student_info = $result->fetch_assoc();
}
$stmt->close();

$grades = [];
$grades_query = "SELECT g.*, s.subject_name 
                FROM student_grades g
                JOIN subjects s ON g.subject_id = s.subject_id
                WHERE g.student_id = ?";

$stmt = $connection->prepare($grades_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$grades_result = $stmt->get_result();

if ($grades_result->num_rows > 0) {
    while ($row = $grades_result->fetch_assoc()) {
        $grades[] = $row;
    }
}
$stmt->close();

// If no student found, redirect back or show error
if (empty($student_info)) {
    // You can redirect or show error message
    // header("Location: advisory_Class.php");
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>



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

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12 h-full">


        <div class="grid grid-cols-1 gap-7 lg:grid-cols-3">

            <!-- 1st Column -->

            <div class="col-span-2">

            <div class="breadcrumbs text-sm mb-3.5">
                <ul>
                    <li><a href="advisory_Class.php?teacher_id=<?php echo htmlspecialchars($teacher_id); ?>">Advisory Class</a></li>
                    <li>View Student</li>
                </ul>
            </div>

                <h1 class="text-lg font-medium mb-3.5">Report on Learning Progress and Achievement</h1>

            
                <div class="rounded bg-teal-100 p-4 mb-7 space-y-2 shadow">
    <?php if (!empty($student_info)): ?>
        <h2 class="text-teal-900 font-semibold text-xl">GRADE: <?php echo htmlspecialchars($student_info['grade_level']); ?> - <?php echo htmlspecialchars($student_info['section_name']); ?></h2>
        <h1 class="font-extrabold text-4xl text-teal-900">
            <?php echo htmlspecialchars($student_info['first_name'] . ' ' . 
                ($student_info['middle_initial'] ? $student_info['middle_initial'] . ' ' : '') . 
                $student_info['last_name']); ?>
        </h1>
        <p class="text-teal-800 text-sm italic">
            Adviser: <?php echo htmlspecialchars($student_info['teacher_fname'] . ' ' . $student_info['teacher_lname']); ?>
        </p>
    <?php else: ?>
        <h2 class="text-teal-900 font-semibold text-xl">Student Information</h2>
        <h1 class="font-extrabold text-4xl text-teal-900">Not Found</h1>
    <?php endif; ?>
</div>


                <div role="tablist" class="tabs tabs-bordered bg-white p-4 border border-gray-200 rounded mb-7 w-full">

                    <!-- Student Grades -->
                    <!-- Student Grades -->
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Grade" checked="checked" />
                    <div role="tabpanel" class="tab-content pt-6">
                        <?php if (!empty($grades)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2 text-left">Subject</th>
                                    <th class="border px-4 py-2 text-left">1st Quarter</th>
                                    <th class="border px-4 py-2 text-left">2nd Quarter</th>
                                    <th class="border px-4 py-2 text-left">3rd Quarter</th>
                                    <th class="border px-4 py-2 text-left">4th Quarter</th>
                                    <th class="border px-4 py-2 text-left">Final Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $row_num = 0; foreach ($grades as $grade): $row_num++; ?>
                                <tr class="<?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>">
                                    <td class="border px-4 py-2 <?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>"><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                                    <td class="border px-4 py-2 <?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>"><?php echo htmlspecialchars($grade['grade1']); ?></td>
                                    <td class="border px-4 py-2 <?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>"><?php echo htmlspecialchars($grade['grade2']); ?></td>
                                    <td class="border px-4 py-2 <?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>"><?php echo htmlspecialchars($grade['grade3']); ?></td>
                                    <td class="border px-4 py-2 <?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>"><?php echo htmlspecialchars($grade['grade4']); ?></td>
                                    <td class="border px-4 py-2 <?php echo $row_num % 2 == 1 ? 'bg-teal-100' : ''; ?>"><?php echo htmlspecialchars($grade['final_grade']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <span>No grades available for this student.</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Behavior Log -->
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Behavior" />
                    <div role="tabpanel" class="tab-content pt-6">
                        <table>
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2 text-left">Core Value</th>
                                    <th class="border px-4 py-2 text-left">Behavior Statements</th>
                                    <th class="border px-4 py-2 text-left">Q1</th>
                                    <th class="border px-4 py-2 text-left">Q2</th>
                                    <th class="border px-4 py-2 text-left">Q3</th>
                                    <th class="border px-4 py-2 text-left">Q4</th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="border px-4 py-2 bg-teal-100">Maka-Diyos</td>
                                    <td class="border px-4 py-2 bg-teal-100">Expresses one's spiritual beliefs while respecting the spiritual beliefs of others</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">SO</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2">Maka-Tao</td>
                                    <td class="border px-4 py-2">Shows adherence to ethical principles by upholding truth</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">SO</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2 bg-teal-100">Makakalikasan</td>
                                    <td class="border px-4 py-2 bg-teal-100">Is sensitive to individual, social and cultural differences Demonstrates contributions towards solidarity</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">SO</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2">Makabansa</td>
                                    <td class="border px-4 py-2">Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen </td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">SO</td>
                                </tr>





                            </tbody>

                        </table>
                    </div>

                    <!-- Comments -->
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Comments" />
                    <div role="tabpanel" class="tab-content p-10">Tab content 3</div>


                </div>

                <!-- learner Progress -->
                <div class="p-4 border border-gray-200 rounded bg-white mb-3.5">
                    <h1 class="font-bold mb-6 text-center">LEARNER PROGRESS AND ACHIEVEMENT</h1>


                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Descriptors</th>
                                <th class="px-4 py-2 text-left">Grading Scale</th>
                                <th class="px-4 py-2 text-left">Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="px-4 py-2">Outstanding</td>
                                <td class="px-4 py-2">90 - 100</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Very Satisfactory</td>
                                <td class="px-4 py-2">85 - 89</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Satisfactory</td>
                                <td class="px-4 py-2">80 - 84</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Fairly Satisfactory</td>
                                <td class="px-4 py-2">75 - 79</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Did not meet Expectations</td>
                                <td class="px-4 py-2">Below 75</td>
                                <td class="px-4 py-2">Failed</td>
                            </tr>

                        </tbody>

                    </table>


                </div>
                <!-- Observed Value -->
                <div class="p-4 border border-gray-200 rounded bg-white">
                    <h1 class="font-bold mb-6 text-center">OBSERVED VALUE</h1>


                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Marking</th>
                                <th class="px-4 py-2 text-left">Non-Numerical Rating</th>

                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="px-4 py-2">AO</td>
                                <td class="px-4 py-2">Always Observed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">SO</td>
                                <td class="px-4 py-2">Sometimes Observed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">RO</td>
                                <td class="px-4 py-2">Rarely Observed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">NO</td>
                                <td class="px-4 py-2">Not Observed</td>
                            </tr>


                        </tbody>

                    </table>


                </div>

            </div>

            <!-- 2nd Column -->
            <div class="p-6 bg-white rounded-md border border-gray-200 space-y-3.5 col-span-1">
    <?php if (!empty($student_info)): ?>
        <!-- Student Photo - You can replace with actual photo path if available -->
       <!-- <img src="../../assets/img/student_photos/<?php echo $student_info['student_id']; ?>.jpg"  -->
        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="" class="h-[330px] w-full object-cover rounded" />

        <div>
            <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Name</p>
            <div class="relative flex items-center">
                <input name="full-name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md bg-teal-50" readonly 
                       value="<?php echo htmlspecialchars($student_info['first_name'] . ' ' . 
                                    ($student_info['middle_initial'] ? $student_info['middle_initial'] . '. ' : '') . 
                                    $student_info['last_name']); ?>" />
            </div>
        </div>

        <div>
            <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Student Number</p>
            <div class="relative flex items-center">
                <input name="student-number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md bg-teal-50" readonly 
                       value="<?php echo htmlspecialchars($student_info['student_number']); ?>" />
            </div>
        </div>

        <div>
            <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Grade Level</p>
            <div class="relative flex items-center">
                <input name="grade-level" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md bg-teal-50" readonly 
                       value="Grade: <?php echo htmlspecialchars($student_info['grade_level']); ?> - <?php echo htmlspecialchars($student_info['section_name']); ?>" />
            </div>
        </div>

        <div>
            <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Academic Year</p>
            <div class="relative flex items-center">
                <input name="academic-year" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md bg-teal-50" readonly 
                       value="<?php echo htmlspecialchars($student_info['school_year']); ?>" />
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            <span>Student information not found.</span>
        </div>
    <?php endif; ?>
</div>





            </div>



        </div>

    </div>











</body>

</html>