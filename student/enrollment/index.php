<?php
session_start();
include '../../config/db.php'; // Include the database connection

// Initialize error message
$error = '';

// Process the form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the student number from the form input
    $student_number = $_POST['student_number'];

    // Check if the student number is empty
    if (empty($student_number)) {
        $error = "Student number is required.";
    } else {
        // Prepare the SQL query to check if the student number exists and if it has been approved
        $sql = "SELECT * FROM admission_form WHERE student_number = ? AND is_confirmed = 1"; // Adjusted column name

        // Prepare the query using MySQLi
        if ($stmt = $connection->prepare($sql)) {
            // Bind the student number to the parameter
            $stmt->bind_param("s", $student_number); // "s" means it's a string
            
            // Execute the statement
            $stmt->execute();
            
            // Store the result
            $stmt->store_result();
            
            // Check if the student number is found and approved
            if ($stmt->num_rows > 0) {
                // Student number is valid and approved
                $_SESSION['student_number'] = $student_number;
                header("Location: enrollmentForm.php"); // Redirect to the enrollment form
                exit();
            } else {
                // Student number is not found or not approved
                $error = "The student number you entered is either not found or not approved.";
            }

            // Close the statement
            $stmt->close();
        } else {
            $error = "Failed to prepare the SQL query.";
        }
    }
}

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
       
            <img src="../../assets/images/logo.png" alt="" class="w-10 h-10 object-cover">
         
        </a>

        <div class="m-auto flex flex-col items-center w-full lg:w-[580px] ">
            <div class="space-y-3 w-full text-center">
                <h5 class="text-2xl font-bold">Enrollment A.Y. 2024-2025</h5>
                <p class="text-slate-500">Begin your enrollment process by providing the required information below.</p>
            </div>
    
            <form method="POST" class="mt-8 space-y-6 w-full">

                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Select School year</label>
                                <div class="relative flex items-center">
                                    <select name="school-year" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="" disabled selected>Select grade level</option>
                                        <option value="2024-2025">2024-2025</option>
                                       
                                    </select>
                                </div>
                            
                        </div>

                        
                        <div>
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
                            
                        </div>
                        
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
                            <button type="submit" class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Proceed to Enrollment
                            </button>
                        </div>
                        <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->
            </form>

            
            <?php if (isset($error)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $error; ?></div>
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
