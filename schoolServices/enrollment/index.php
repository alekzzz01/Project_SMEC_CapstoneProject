<?php
session_start();
include '../../config/db.php'; // Include the database connection
require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../../config');
$dotenv->load();

if (isset($_POST['proceed'])) {
    // Retrieve POST variables
    $school_year = $_POST['school-year'];
    $student_number = $_POST['student_number'];
    $recaptcha_response = $_POST['g-recaptcha-response']; // Get reCAPTCHA response

    // Verify reCAPTCHA
    $secretKey = $_ENV['RECAPTCHA_SECRET'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($url . '?secret=' . $secretKey . '&response=' . $recaptcha_response . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        $_SESSION['error'] = "reCAPTCHA verification failed. Please try again.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Step 1: Get the active school year
    $getSchoolYearSQL = "SELECT school_year_id FROM school_year WHERE status = 'Open' LIMIT 1";
    if ($stmt = $connection->prepare($getSchoolYearSQL)) {
        $stmt->execute();
        $stmt->bind_result($school_year_id);
        $stmt->fetch();
        $stmt->close();

        if (!$school_year_id) {
            $_SESSION['warning'] = "No active school year found.";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error'] = "Error retrieving school year data.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

     // Step 2: Check if the student is already enrolled in the active school year
     $checkEnrollmentSQL = "SELECT COUNT(*) FROM student_enrollment WHERE student_id = (SELECT student_id FROM students WHERE student_number = ?) AND school_year_id = ?";
     if ($stmt = $connection->prepare($checkEnrollmentSQL)) {
         $stmt->bind_param("si", $student_number, $school_year_id);
         $stmt->execute();
         $stmt->bind_result($enrollmentCount);
         $stmt->fetch();
         $stmt->close();
 
         if ($enrollmentCount > 0) {
             $_SESSION['error'] = "The student is already enrolled for this school year.";
             header('Location: ' . $_SERVER['PHP_SELF']);
             exit();
         }
     } else {
         $_SESSION['error'] = "Error checking enrollment status.";
         header('Location: ' . $_SERVER['PHP_SELF']);
         exit();
     }

    // Step 3: Check if the student number exists in the database
    $studentQuery = "SELECT * FROM students WHERE student_number = ?";
    if ($stmt = $connection->prepare($studentQuery)) {
        $stmt->bind_param("s", $student_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();

        if ($student) {
            // Store student data in session
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_number'] = $student['student_number'];
            $_SESSION['first_name'] = $student['first_name'];
            $_SESSION['last_name'] = $student['last_name'];
            $_SESSION['date_of_birth'] = $student['date_of_birth'];
            $_SESSION['gender'] = $student['gender'];
            $_SESSION['contact_number'] = $student['contact_number'];
            

            // Redirect to the enrollment form
            header("Location: enrollmentForm.php");
            exit();
        } else {
            $_SESSION['warning'] = "Student number not found. Please enter a valid student number.";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error'] = "Error retrieving student data.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

$warning = $_SESSION['warning'] ?? null;
unset($_SESSION['warning']);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment - SMEC</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>


</head>



<body class="h-screen">
    


    <div class="grid grid-cols-1 xl:grid-cols-2 h-full gap-2">

    <div class="flex flex-col  justify-between p-4 space-y-12">

        
        <a class="flex items-center gap-4" href="../../">
       
            <img src="./../../assets/images/defaultLogo.png" alt="" class="w-10 h-10 object-cover">
         
        </a>

        <div class="m-auto flex flex-col items-center w-full lg:w-[580px] ">
            <div class="space-y-3 w-full text-center">
                <h5 class="text-2xl font-bold">Enrollment 
                    <?php
                    $schoolYearQuery = "SELECT school_year FROM school_year WHERE status = 'open' LIMIT 1";
                    $schoolYearResult = $connection->query($schoolYearQuery);
                    if ($schoolYearResult->num_rows > 0) {
                        $schoolYear = $schoolYearResult->fetch_assoc();
                        echo "for A.Y " . $schoolYear['school_year'];
                    } else {
                        echo "Closed";
                    }
                    
                    ?>
                </h5>
                <p class="text-slate-500">Begin your enrollment process by providing the required information below.</p>
            </div>
    
            <form method="POST" class="mt-8 space-y-6 w-full">

                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Select School year</label>
                                <div class="relative flex items-center">
                                    <select name="school-year" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="">Select school year</option>
                                            <?php
                                                // Fetch distinct school years with status 'open' for the filter dropdown
                                            $schoolYearQuery = "SELECT DISTINCT school_year FROM school_year WHERE status = 'open' ORDER BY school_year ASC";
                                            $schoolYearResult = $connection->query($schoolYearQuery);
                                            if ($schoolYearResult->num_rows > 0) {
                                                while ($row = $schoolYearResult->fetch_assoc()) {
                                                    echo "<option value='{$row['school_year']}'>{$row['school_year']}</option>";
                                                }
                                            }
                                        ?>
    
                                    </select>
                                </div>
                            
                        </div>

                        
                        <!-- <div>
                            <label class="text-gray-800 text-sm mb-2 block">Select Grade Level</label>
                                <div class="relative flex items-center">
                                    <select name="grade-level" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="" disabled selected>Select grade level</option>
                                        <option value="grade-1">Grade 1</option>
                                        <option value="grade-2">Grade 2</option>
                                        <option value="grade-3">Grade 3</option>
                                        <option value="grade-4">Grade 4</option>
                                        <option value="grade-5">Grade 5</option>
                                        <option value="grade-6">Grade 6</option>
                                        <option value="grade-7">Grade 7</option>
                                        <option value="grade-8">Grade 8</option>
                                        <option value="grade-9">Grade 9</option>
                                        <option value="grade-10">Grade 10</option>
                                        <option value="grade-11">Grade 11</option>
                                        <option value="grade-12">Grade 12</option>
                                    </select>
                                </div>
                            
                        </div> -->
                        
                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Student Number</label>
                            <div class="relative flex items-center">
                            <input name="student_number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your student Number" />
                        
                            </div>
                        </div>

                     

                        <div class="flex flex-wrap items-center justify-end mx-3">
                           

                            <div class="text-sm">
                            <a href="jajvascript:void(0);" class="text-blue-600 hover:underline font-semibold">
                                Need Help?
                            </a>
                            </div>
                        </div>

                        <div class="!mt-8">
                            <button type="submit" name="proceed" class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Proceed to Enrollment
                            </button>
                        </div>
                        <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->

                        <div class="flex items-center justify-center">
                            <div class="g-recaptcha" data-sitekey="6LfIZ5EqAAAAAGeXLXbd-FE6FjKxV-VKz4wfSLM2"></div>
                        </div>
            </form>

            
            <?php if (isset($error)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($warning)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $warning; ?></div>
            <?php endif; ?>


        </div>

        <div class="text-xs text-gray-400  flex items-center justify-between">
            <p class="hover:text-black transition-colors">© 2024 Lumix. All Rights Reserved.</p>
            <div class="flex items-center gap-6">
                <a href="" class="hover:text-black transition-colors">Privacy</a>
                <a href="" class="hover:text-black transition-colors">Cookies Policy</a>
            </div>
        </div>


     
    </div>


    <div class="w-full h-full hidden xl:grid justify-center items-center bg-blue-200">
                <img src="../../assets/images/enrollmentAnim.gif" alt="" class=" h-1/2 w-full">
    </div>





    </div>





    
</body>
</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
