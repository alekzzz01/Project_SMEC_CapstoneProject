<?php 

session_start();
include '../../config/db.php'; // Include the database connection

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    $student_number = $_SESSION['student_number'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $date_of_birth = $_SESSION['date_of_birth'];
    $gender = $_SESSION['gender'];
    $contact_number = $_SESSION['contact_number'];
} else {
    // Redirect back if session variables are not set
    header("Location: index.php");
    exit();
}

if (isset($_POST['submitForm'])) {
    $student_type = $_POST['student-type'];
    $type = $_POST['type']; // Add the $type variable here
    $school_year_id = $_POST['school-year'];
    $grade_level = $_POST['grade-level'];
    $track = isset($_POST['track']) ? $_POST['track'] : null;
    $parent_contact_number = $_POST['parent-contact-number'];
    $last_school_attended = $_POST['last-school-attended'];

    // Initialize file variables
    $birth_certificate = null;
    $report_card = null;
    $good_moral_certificate = null;

    // Check and retrieve uploaded files
    if (isset($_FILES['birth_certificate']) && $_FILES['birth_certificate']['error'] === 0) {
        $birth_certificate = file_get_contents($_FILES['birth_certificate']['tmp_name']);
    }

    if (isset($_FILES['report_card']) && $_FILES['report_card']['error'] === 0) {
        $report_card = file_get_contents($_FILES['report_card']['tmp_name']);
    }

    if (isset($_FILES['good_moral_certificate']) && $_FILES['good_moral_certificate']['error'] === 0) {
        $good_moral_certificate = file_get_contents($_FILES['good_moral_certificate']['tmp_name']);
    }

    $sql = "
        INSERT INTO student_enrollment (
            student_id, student_type, type, school_year_id, grade_level, 
            track, date_enrolled, parent_contact_number, last_school_attended,
            birth_certificate, report_card, good_moral_certificate, status
        ) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, 'Pending')
    ";

    if ($stmt = $connection->prepare($sql)) {
        if (empty($track)) {
            $track = NULL; // If track is empty, set it as NULL
        }

        // Bind parameters
        $stmt->bind_param(
            "ississssbbb", 
            $student_id, 
            $student_type, 
            $type, 
            $school_year_id, 
            $grade_level, 
            $track, 
            $parent_contact_number, 
            $last_school_attended, 
            $birth_certificate, 
            $report_card, 
            $good_moral_certificate
        );

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['enrollment_form_success_message'] = "Enrollment form submitted successfully!";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['enrollment_form_error'] = "Error: Unable to submit enrollment form.";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['enrollment_form_error'] = "Error: Unable to prepare statement.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
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

            <a class="flex items-center gap-4 " href="">
                <img src="../../assets/images/logo.png" alt="" class="w-10 h-10 object-cover bg-white rounded-full">
                <p class="text-2xl font-medium tracking-tighter hidden lg:block">Sta. Marta Educational Center Inc.</p>
            </a>


            <div class="flex items-center ">
                
                <!-- Initial Items Menu -->
                <ul class="menu menu-horizontal px-1 font-medium hidden lg:flex ">
            
                
                <li><a href="logout.php">LOGOUT</a></li>

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

    <div class="py-16 px-4 lg:px-12 bg-[#f2f5f8]"> 
            
            <div class=" max-w-7xl mx-auto space-y-7">

           
               
                <?php if (isset($_SESSION['message'])): ?>
                        <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7"   ><?= $_SESSION['message']; ?></div>
                        <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                        <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7" ><?= $_SESSION['error']; ?></div>
                        <?php unset($_SESSION['error']); ?>
                <?php endif; ?>


                <form action="" method="POST" enctype="multipart/form-data" class="space-y-10">
                    
                        <div class="border border-gray-300 rounded bg-white">
                            <!-- Name -->
                            <h1 class="text-lg font-bold p-5 bg-blue-50 rounded-t text-blue-600">Personal Details <span class="text-red-500">*</span></h1>

                       
                       
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full p-5">
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">First Name</label>
                                        <div class="relative flex items-center">
                                        <input name="first-name" type="text" class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo htmlspecialchars($first_name); ?>" />
                                    
                                        </div>
                                       
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Middle Initial</label>
                                        <div class="relative flex items-center">
                                        <input name="middle-initial" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                    
                                        </div>
                                       
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Last Name</label>
                                        <div class="relative flex items-center">
                                        <input name="last-name" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo htmlspecialchars($last_name); ?>" />
                                    
                                        </div>
                                       
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">LRN</label>
                                        <div class="relative flex items-center">
                                        <input name="lrn" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  />
                                    
                                        </div>
                                     
                                    </div>

                                    
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Birth Date</label>
                                        <div class="relative flex items-center">
                                        <input name="birth-date" type="date"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" value="<?php echo htmlspecialchars($date_of_birth); ?>" />
                                    
                                        </div>
                                    
                                    </div>

                                            
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Gender</label>
                                        <div class="relative flex items-center">
                                            <select name="grade-level" class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                                <option value="male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                <option value="female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                            </select>
                                        </div>
                                    </div>



                                
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Mobile Number</label>
                                        <div class="relative flex items-center">
                                        <input name="mobile-number" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo htmlspecialchars($contact_number); ?>"/>
                                    
                                        </div>
                                    
                                    </div>


                             
                            </div>

          
                          
                        </div>


                        <!-- Parent/Guardian Name -->

                        <div class="border border-gray-300 rounded bg-white">


                         
                            <h1 class="text-lg font-bold p-5 bg-blue-50 rounded-t text-blue-600">Emergency Contact <span class="text-red-500">*</span></h1>

                            <div class="p-5">                          
                            
                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                    <div>
                                        <p class="text-gray-800 text-sm font-medium mb-2 block">First Name</p>
                                        <div class="relative flex items-center">
                                        <input name="parent-first-name" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-gray-800 text-sm font-medium mb-2 block">Middle Initial</p>
                                        <div class="relative flex items-center">
                                        <input name="parent-middle-initial" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                    
                                        </div>
                                       
                                    </div>

                                    <div>
                                        <p class="text-gray-800 text-sm font-medium mb-2 block">Last Name</p>
                                        <div class="relative flex items-center">
                                        <input name="parent-last-name" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                    
                                        </div>
                                      
                                    </div>

                                    <div>
                                        <p class="text-gray-800 text-sm font-medium mb-2 block">Contact Number</p>
                                        <div class="relative flex items-center">
                                        <input name="parent-contact-number" type="text"  class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                    
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- Academic -->

                        <div class="border border-gray-300 rounded bg-white">
                        
                            <h1 class="text-lg font-bold p-5 bg-blue-50 text-blue-600">Academic <span class="text-red-500">*</span></h1>

                            <!-- Student Type -->

                            <div class="flex flex-col gap-4 p-5">

                                    <div class="flex items-center gap-3">
                                            <input type="radio" name="student-type" class="radio radio-info" value="New Student" required />
                                            <span>New Student</span>
                                    </div>

                                    <div class="flex items-center gap-3">
                                            <input type="radio" name="student-type" class="radio radio-info" value="Transferee" required  />
                                            <span>Transferee</span>
                                    </div>

                                    <div class="flex items-center gap-3">
                                            <input type="radio" name="student-type" class="radio radio-info" value="Returning Student" required  />
                                            <span>Returning Student</span>
                                    </div>

                            </div>

                             <!-- Grade level, School year, Last School Attended -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full p-5">

                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-2 block">School Year</label>
                                    <div class="relative flex items-center">
                                    <select name="school-year" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="">Select school year</option>
                                        <?php
                                            // Fetch distinct school years with status 'open' and include the ID for each
                                            $schoolYearQuery = "SELECT school_year_id, school_year FROM school_year WHERE status = 'open' ORDER BY school_year ASC";
                                            $schoolYearResult = $connection->query($schoolYearQuery);
                                            if ($schoolYearResult->num_rows > 0) {
                                                while ($row = $schoolYearResult->fetch_assoc()) {
                                                    echo "<option value='{$row['school_year_id']}'>{$row['school_year']}</option>";
                                                }
                                            }
                                        ?>
                                    </select>

                                    </div>

                                </div>

                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-2 block">Type</label>
                                    <div class="relative flex items-center">
                                        <select id="typeSelect" name="type" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                            <option value="" disabled selected>Select type</option>
                                            <option value="Preschool">Preschool</option>
                                            <option value="Elementary">Elementary</option>
                                            <option value="JHS">Junior High School</option>
                                            <option value="SHS">Senior High School</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-2 block">Grade Level</label>
                                    <div class="relative flex items-center">
                                        <select id="gradeLevelSelect" name="grade-level" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                            <option value="" disabled selected>Select grade level</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="trackContainer" style="display: none;">
                                    <label class="text-gray-800 text-sm font-medium mb-2 block">Senior High School Program</label>
                                    <div class="relative flex items-center">
                                        <select id="trackSelect" name="track" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                            <option value="" disabled selected>Select Track</option>
                                            <option value="abm-track">ABM Track</option>
                                            <option value="gas-track">GAS Track</option>
                                        </select>
                                    </div>
                                </div>


                            </div>
                        
                        </div>

                        <!-- File Inputs -->
                        <div class="border border-gray-300 rounded bg-white">
                
                    
                            <h1 class="text-lg font-bold bg-blue-50 text-blue-600 p-5">For New Student <span class="text-red-500">*</span></h1>
                        
                          
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full p-5">

                    

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Birth Certificate</label>
                                            <div class="relative flex items-center">
                                            <input name="birth-certificate" type="file" enctype="multipart/form-data"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
            
                                            </div>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Report Card</label>
                                            <div class="relative flex items-center">
                                            <input name="report-card" type="file" enctype="multipart/form-data"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
            
                                            </div>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Good Moral Certificate</label>
                                            <div class="relative flex items-center">
                                            <input name="good-moral-certificate" type="file" enctype="multipart/form-data"   class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
            
                                            </div>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Last School Attended</label>
                                            <div class="relative flex items-center">
                                            <input name="last-school-attended" type="text" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"/>
                                        
                                            </div>
                                    
                                    </div>

                                    <p class="text-gray-400 text-sm">*Note: New student should submit their birth certificate, report card and good moral certificate.</p>



                            </div>

                        </div>   
                        
                    
                        <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox"  required class="checkbox border-black" />
                                    <p class="font-medium text-sm">I confirm that the information provided is accurate.</p>
                                </div>
                                <p class="text-gray-400 text-sm">By checking this box, you agree to our <a class="text-black hover:underline">Terms and Conditions</a> and <a class="text-black hover:underline">Privacy Policy.</a></p>
                        </div>

                            
                        <div class=" flex items-center justify-end">
                            <button type="submit" name="submitForm" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit Form</button>
                        </div>


          

                </form>
            </div>
            

                
    

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

        
    </div>
  

    
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


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the dropdown elements
        const typeSelect = document.getElementById("typeSelect");
        const gradeLevelSelect = document.getElementById("gradeLevelSelect");
        const trackContainer = document.getElementById("trackContainer");
        const trackSelect = document.getElementById("trackSelect");

        // Define grade level options based on type
        const gradeOptions = {
            Preschool: [
                { value: "nursery", text: "Nursery" },
                { value: "kinder", text: "Kinder" },
                { value: "prepatory", text: "Preparatory" },
            ],
            Elementary: [
                { value: "grade-1", text: "Grade 1" },
                { value: "grade-2", text: "Grade 2" },
                { value: "grade-3", text: "Grade 3" },
                { value: "grade-4", text: "Grade 4" },
                { value: "grade-5", text: "Grade 5" },
                { value: "grade-6", text: "Grade 6" },
            ],
            JHS: [
                { value: "grade-7", text: "Grade 7" },
                { value: "grade-8", text: "Grade 8" },
                { value: "grade-9", text: "Grade 9" },
                { value: "grade-10", text: "Grade 10" },
            ],
            SHS: [
                { value: "grade-11", text: "Grade 11" },
                { value: "grade-12", text: "Grade 12" },
            ],
        };

        // Event listener for type selection
        typeSelect.addEventListener("change", function () {
            const selectedType = this.value;

            // Reset grade level options
            gradeLevelSelect.innerHTML = '<option value="" disabled selected>Select grade level</option>';

            if (selectedType in gradeOptions) {
                // Populate grade level options based on selected type
                gradeOptions[selectedType].forEach(option => {
                    const opt = document.createElement("option");
                    opt.value = option.value;
                    opt.textContent = option.text;
                    gradeLevelSelect.appendChild(opt);
                });

                // Show or hide track dropdown
                if (selectedType === "SHS") {
                    trackContainer.style.display = "block";
                } else {
                    trackContainer.style.display = "none";
                    trackSelect.value = ""; // Reset track selection if hidden
                }
            }
        });
    });
</script>
