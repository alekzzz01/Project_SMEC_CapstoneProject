<?php

include '../../config/db.php';

// Function to calculate age based on birthday
function calculateAge($birthday) {
    $birthDate = new DateTime($birthday);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

// Check if `student_id` is set in the URL
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']); // Use intval to ensure it's an integer


    // Query to fetch data from admission_form, students, and student_enrollment tables
    $stmt = $connection->prepare("
    SELECT 
        admission_form.first_name,
        admission_form.middle_initial,
        admission_form.last_name,
        admission_form.birth_date AS birthday,
        admission_form.gender,
        CONCAT_WS(', ', admission_form.barangay, admission_form.city, admission_form.province, admission_form.region) AS address,
        admission_form.city,
        admission_form.barangay,
        admission_form.province,
        admission_form.zip_code,
        students.year_level AS `grade_level`,
        CONCAT(
            COALESCE(students.parent_first_name, ''), ' ', 
            COALESCE(students.parent_middle_initial, ''), ' ', 
            COALESCE(students.parent_last_name, '')
        ) AS guardian_full_name,
        students.relationship AS guardian_relationship,
        student_enrollment.parent_contact_number AS guardian_contact_number,
        school_year.school_year AS `academic_year`
    FROM 
        students
    JOIN 
        admission_form 
    ON 
        students.email = admission_form.email
    JOIN 
        student_enrollment 
    ON 
        students.student_id = student_enrollment.student_id
    JOIN
        school_year
    ON
        student_enrollment.school_year_id = school_year.school_year_id
    WHERE 
        students.student_id = ? AND school_year.status = 'Open'
");

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

    // Check if a student with this ID exists
    if ($result->num_rows > 0) {
        // Fetch the student's details
        $student = $result->fetch_assoc();

        // Calculate the age if a valid birthday exists
        if (!empty($student['birthday'])) {
            $student['age'] = calculateAge($student['birthday']);
        } else {
            $student['age'] = 'Field is Blank'; // Handle cases where birthday is missing
        }

        // Check for blank fields
        foreach ($student as $key => $value) {
            if (empty($value)) {
                $student[$key] = 'Field is Blank'; // Mark blank fields
            }
        }
    } else {
        echo "No student found with ID: " . htmlspecialchars($student_id);
        exit;
    }
} else {
    echo "No student ID provided!";
    exit;
}

// Close the statement and connection
$stmt->close();
$connection->close();

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
 
    <script src="https://cdn.tailwindcss.com"></script>
 
    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">

 
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

     
    <html data-theme="light"></html>
    






</head>
<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">

        <!-- Navbar -->

        <?php include('./components/navbar.php'); ?>
        
        <!-- Content -->

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-6 bg-[#f2f5f8] h-full">
            
            <div class="p-6 bg-white rounded-md border border-gray-200 space-y-3.5 col-span-1">
                  
                    <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="" class="h-[330px] w-full object-cover rounded" />
                  
                    <div class="p-2 rounded border border-gray-200 text-center">
                                <p class="font-bold text-amber-400">PENDING</p>
                                <p class="font-light">Status</p>
                    </div>

                    <div>
                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Grade Level</p>
                            <div class="relative flex items-center">
                            <input name="grade-level" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['grade_level']); ?>" />
                        
                            </div>
                       
                    </div>

                    <div>
                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Academic Year</p>
                            <div class="relative flex items-center">
                            <input name="first_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['academic_year']); ?>" />
                        
                            </div>
                       
                    </div>



              

                
            </div>

            <div class="p-6 bg-white rounded-md border border-gray-200 w-full col-span-1 lg:col-span-3">

                <div role="tablist" class="tabs tabs-sm tabs-bordered">

                <!-- Personal Details -->
                <input type="radio" name="my_tabs_1" role="tab" class="tab"  checked="checked" aria-label="Basic Info" />
                <div role="tabpanel" class="tab-content pt-14 px-4 space-y-6">
                    <h1 class="text-lg font-bold">Personal Details <span class="text-red-500">*</span></h1>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">First Name</p>
                                        <div class="relative flex items-center">
                                        <input name="first-name" type="text" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly  value="<?php echo htmlspecialchars($student['first_name']); ?>" />
                                    
                                        </div>
                                      
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Middle Initial</p>
                                        <div class="relative flex items-center">
                                        <input name="middle-initial" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['middle_initial']); ?>" />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Last Name </p>
                                        <div class="relative flex items-center">
                                        <input name="last-name" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly  value="<?php echo htmlspecialchars($student['last_name']); ?>" />
                                    
                                        </div>
                                
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Gender</p>
                                        <div class="relative flex items-center">
                                        <input name="gender" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['gender']); ?>" />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Age</p>
                                        <div class="relative flex items-center">
                                        <input name="age" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['age']); ?>"  />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Civil Status</p>
                                        <div class="relative flex items-center">
                                        <input name="civil-status" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['civil_status']); ?>"  />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Religion</p>
                                        <div class="relative flex items-center">
                                        <input name="religion" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['religion']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Citizenship</p>
                                        <div class="relative flex items-center">
                                        <input name="citizenship" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['citizenship']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Birthday</p>
                                        <div class="relative flex items-center">
                                        <input name="birthday" type="date"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['birthday']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>


                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Birthplace</p>
                                        <div class="relative flex items-center">
                                        <input name="birth-place" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['birth_place']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Address</p>
                                        <div class="relative flex items-center">
                                        <input name="address" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['address']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>
                    </div>


                </div>


                <!-- Guardian Information -->

                <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Guardian Info" />
                <div role="tabpanel" class="tab-content pt-14 px-4 space-y-6">

                    <h1 class="text-lg font-bold">Guardian Details <span class="text-red-500">*</span></h1>
                  
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">

                    
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Full Name</p>
                                        <div class="relative flex items-center">
                                        <input name="guardian-full-name" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['guardian_full_name']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                                    
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Contact No.</p>
                                        <div class="relative flex items-center">
                                        <input name="guardian_contact_number" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['guardian_contact_number']); ?>"  />
                                    
                                        </div>
                                    
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Relationship to the Student</p>
                                        <div class="relative flex items-center">
                                        <input name="guardian_relationship" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['guardian_relationship']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                    </div>



                </div>



                <!-- Document Submitted -->


                <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Documents" />
                <div role="tabpanel" class="tab-content pt-14 px-4 space-y-6">
                    <h1 class="text-lg font-bold">Basic Requirements <span class="text-red-500">*</span></h1>
                  
                    <div class="w-full">

                                <div class="border border-gray-200 rounded flex items-center justify-between px-4 py-2 bg-amber-50">
                                        <p>Birth Certificate</p>

                                        <div class="tooltip tooltip-bottom" data-tip="Download Document">
                                            <button class="btn btn-ghost">  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                            </button>

                                        </div>

                                      

    
                                </div>

                    </div>


                </div>
                
            </div>






            
        </div>

    
    </div>


</body>
</html>