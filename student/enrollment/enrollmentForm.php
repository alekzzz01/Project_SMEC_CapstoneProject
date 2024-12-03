<?php
session_start();

include '../../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = $_POST['first-name'];
    $middle_initial = $_POST['middle-initial'];
    $last_name = $_POST['last-name'];
    $lrn = $_POST['lrn'];
    $birth_date = $_POST['birth-date'];
    $gender = $_POST['gender'];
    $mobile_number = $_POST['mobile-number'];
    $parent_first_name = $_POST['parent-first-name'];
    $parent_middle_initial = $_POST['parent-middle-initial'];
    $parent_last_name = $_POST['parent-last-name'];
    $parent_contact_number = $_POST['parent-contact-number'];
    $student_type = isset($_POST['new-student']) ? 'New Student' : (isset($_POST['transferee']) ? 'Transferee' : 'Returning Student');
    $grade_level = $_POST['grade-level'];
    $school_year = $_POST['school-year'];
    $last_school_attended = $_POST['last-school-attended'];

    // Handle file uploads (if necessary, but ignoring for now)
    $birth_certificate = file_get_contents($_FILES['birth-certificate']['tmp_name']);
    $report_card = file_get_contents($_FILES['report-card']['tmp_name']);
    $good_moral_certificate = file_get_contents($_FILES['good-moral-certificate']['tmp_name']);

    // Prepare SQL query using prepared statements
    $query = "INSERT INTO enrollment (first_name, middle_initial, last_name, lrn, birth_date, gender, mobile_number, parent_first_name, parent_middle_initial, parent_last_name, parent_contact_number, student_type, grade_level, school_year, last_school_attended, birth_certificate, report_card, good_moral_certificate) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $connection->prepare($query);

    // Bind the parameters to the prepared statement
    $stmt->bind_param("ssssssssssssssssss", 
    $first_name, 
    $middle_initial, 
    $last_name, 
    $lrn, 
    $birth_date, 
    $gender, 
    $mobile_number, 
    $parent_first_name, 
    $parent_middle_initial, 
    $parent_last_name, 
    $parent_contact_number, 
    $student_type, 
    $grade_level, 
    $school_year, 
    $last_school_attended, 
    $birth_certificate, 
    $report_card, 
    $good_moral_certificate
);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to avoid resubmission (PRG pattern)
        $_SESSION['formSubmitted'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment</title>

       
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   

    <!--JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

     
     
</head>
<body>

    <div id="navbar" class="px-4 py-2 fixed w-full top-0 left-0 z-10 transition duration-300 text-white">
        <div class=" flex items-center justify-between">

            <a class="flex items-center gap-4 " href="./">
                <img src="../../assets/images/logo.png" alt="" class="w-10 h-10 object-cover bg-white rounded-full">
                <p class="text-2xl font-medium tracking-tighter hidden lg:block">Sta. Marta Educational Center Inc.</p>
            </a>


            <div class="flex items-center ">
                
                <!-- Initial Items Menu -->
                <ul class="menu menu-horizontal px-1 font-medium hidden lg:flex ">
            
                
                <li><a href="../../auth/login.php">LOGOUT</a></li>

                </ul>

                <!-- Small Screen Menu -->
                <div>
                    <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost lg:hidden text-white">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        class="inline-block h-5 w-5 stroke-current">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    </div>
                        <ul
                        tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                      
                        <li><a href="../../auth/login.php">LOGOUT</a></li>
                        </ul>
                    </div>

                </div>

            </div>



        </div>









    </div>




  
    <div class="py-36 px-4 lg:px-12 bg-blue-800 border-b border-gray-100 "> 
            
            <div class="space-y-6  max-w-7xl mx-auto text-white">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-extrabold mb-1">Enrollment Form</h2>

                    <p class="font-light">Enrollment for A.Y. 2022 - 2023 is open!</p>
                </div>
               
            </div>

    </div>

    <div class="py-16 px-4 lg:px-12"> 
            
            <div class=" max-w-7xl mx-auto space-y-7">

                <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                        <h1 class="text-lg font-bold">Personal Details <span class="text-red-500">*</span></h1>
                        <!-- Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Name</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                <div>
                                    <div class="relative flex items-center">
                                    <input name="first-name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">First Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="middle-initial" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="last-name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="lrn" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">LRN</p>
                                </div>
                            </div>
                        </div>

                        <!-- Birthdate, Gender and Year Level -->
                        <div>
                        
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                
                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-6 block">Birth Date</label>
                                    <div class="relative flex items-center">
                                    <input name="birth-date" type="date" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" />
                                
                                    </div>
                                
                                </div>

                                <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Gender</label>
                                    <div class="relative flex items-center">
                                    <input name="gender" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                
                                    </div>
                                
                                </div>

                              

                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-6 block">Mobile Number</label>
                                    <div class="relative flex items-center">
                                    <input name="mobile-number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                
                                    </div>
                                
                                </div>


                            

                        
                            </div>
                        </div>

                        <!-- Parent/Guardian Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Parent/Guardian Name</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                <div>
                                    <div class="relative flex items-center">
                                    <input name="parent-first-name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">First Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="parent-middle-initial" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="parent-last-name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="parent-contact-number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Contact Number</p>
                                </div>
                            </div>
                        </div>




                <div class="border-b border-gray-100"></div>


                    
                    <h1 class="text-lg font-bold">Academic <span class="text-red-500">*</span></h1>

                    <!-- Student Type -->

                    <div class="flex flex-col gap-4">

                            <div class="flex items-center gap-3">
                                    <input type="radio" name="new-student" class="radio radio-info" />
                                    <span>New Student</span>
                            </div>

                            <div class="flex items-center gap-3">
                                    <input type="radio" name="transferee" class="radio radio-info" />
                                    <span>Transferee</span>
                            </div>

                            <div class="flex items-center gap-3">
                                    <input type="radio" name="returning-student" class="radio radio-info" />
                                    <span>Returning Student</span>
                            </div>

                    </div>
                    
            
                    <!-- Grade level, School year, Last School Attended -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">

                        
                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Grade Level</label>
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
                                <label class="text-gray-800 text-sm font-medium mb-6 block">School Year</label>
                                <div class="relative flex items-center">
                                    <select name="school-year" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="" disabled selected>Select school year</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2024-2025">2024-2025</option>
                                    </select>
                                </div>
                            
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Last School Attended</label>
                                    <div class="relative flex items-center">
                                    <input name="last-school-attended" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                
                                    </div>
                               
                            </div>
                            


                       

                        

                    
                    </div>

                    <!-- File Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Birth Certificate</label>
                                    <div class="relative flex items-center">
                                    <input name="birth-certificate" type="file" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
    
                                    </div>
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Report Card</label>
                                    <div class="relative flex items-center">
                                    <input name="report-card" type="file" required  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
    
                                    </div>
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Good Moral Certificate</label>
                                    <div class="relative flex items-center">
                                    <input name="good-moral-certificate" type="file" required  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
    
                                    </div>
                            </div>

                            <p class="text-gray-400 text-sm">*Note: New student should submit their birth certificate, report card and good moral certificate.</p>



                    </div>

                    <div class="border-gray-100 border-b"></div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" checked="checked" class="checkbox" />
                            <p class="font-medium text-sm">I confirm that the information provided is accurate.</p>
                        </div>
                        <p class="text-gray-400 text-sm">By checking this box, you agree to our <a class="text-black hover:underline">Terms and Conditions</a> and <a class="text-black hover:underline">Privacy Policy.</a></p>
                    </div>

                    
                    <div class=" flex items-center justify-end">
                    <button type="submit" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit Form</button>
                </div>

                </form>

                <div class="border-gray-100 border-b"></div>

                
    

                <?php if (isset($_SESSION['formSubmitted']) && $_SESSION['formSubmitted'] === true): ?>
                        <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
                            <div class="modal-box">
                                <h3 class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-lg font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    Enrollment Submitted Successfully!</h3>
                                <p class="py-4 text-gray-400 text-sm">Thank you for submitting your enrollment form. Please follow these next steps:</p>
                                <!-- Instructions -->
                                <div class="py-4">
                                    <ol class="list-decimal list-inside space-y-2">
                                        <li>Visit the school registrar's office within the next 5 business days.</li>
                                        <li>Bring the following documents:
                                            <ul class="list-disc list-inside ml-4 mt-1">
                                                <li>Valid ID</li>
                                                <li>Original and photocopy of your diploma or transcript</li>
                                                <li>2 recent passport-sized photos</li>
                                            </ul>
                                        </li>
                                        <li>Be prepared to pay the enrollment fee at the cashier's office.</li>
                                        <li>Collect your student ID and class schedule from the registrar.</li>
                                    </ol>
                                </div>
                                <div class="modal-action">
                                    <form method="dialog" action="">
                                        <a href="dashboard.php" class="btn">Close</a>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                        <script>
                            document.getElementById("my_modal_5").showModal();
                        </script>
                        <?php unset($_SESSION['formSubmitted']); ?>
                    <?php endif; ?>

        

  

    
</body>
</html>





<script>
    document.addEventListener("scroll", function () {
    const navbar = document.getElementById("navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("bg-blue-800");
    } else {
        navbar.classList.remove("bg-blue-800");
    }
    });

</script>




