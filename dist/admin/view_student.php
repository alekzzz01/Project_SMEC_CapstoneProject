<?php 
include '../../config/db.php';
session_start();

// Check if `student_id` and the action to download are set
if (isset($_GET['student_id']) && isset($_GET['action']) && $_GET['action'] == 'download') {
    $student_id = intval($_GET['student_id']); // Ensure it's an integer
    
    // Fetch the file data (BLOB) and student's surname from the student_enrollment table
    $stmt = $connection->prepare("SELECT birth_certificate FROM student_enrollment WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind the result to the variable
        $stmt->bind_result($birth_certificate);
        $stmt->fetch();

        // Clear any previous output to prevent errors
        ob_clean();

        // Use PHP's finfo functions to detect the MIME type of the file
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Return MIME type
        $mime_type = finfo_buffer($finfo, $birth_certificate);
        finfo_close($finfo);

        // Fetch the student's surname for the filename
        $stmt = $connection->prepare("SELECT last_name FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $surname = $student['last_name'];

        // Set the default filename as surname + "birth_certificate"
        $filename = $surname . '_birth_certificate';

        // Check MIME type and set the correct file extension
        switch ($mime_type) {
            case 'application/pdf':
                $filename .= '.pdf';
                break;
            case 'image/jpeg':
                $filename .= '.jpg';
                break;
            case 'image/png':
                $filename .= '.png';
                break;
            // Add more cases as needed
            default:
                $filename .= '.bin'; // Default to .bin for binary files
        }

        // Set headers to force download
        header('Content-Type: ' . $mime_type); // Set the correct MIME type
        header('Content-Disposition: attachment; filename="' . $filename . '"'); // Set the filename with extension
        header('Content-Length: ' . strlen($birth_certificate)); // Set the file size

        // Output the BLOB data (birth certificate)
        echo $birth_certificate;
        exit;
    } else {
        echo "No birth certificate file found for the student.";
        exit;
    }

    // Close the statement
    $stmt->close();
    $connection->close();
}

// Check if `student_id` is set in the URL for fetching student details
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']); // Ensure it's an integer
    // Fetch student details using a prepared statement
    $stmt = $connection->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a student with this ID exists
    if ($result->num_rows > 0) {
        // Fetch the student's details
        $student = $result->fetch_assoc();
              
        // Calculate the age
        $dob = new DateTime($student['date_of_birth']);
        $today = new DateTime(); // Get the current date
        $age = $today->diff($dob)->y; // Calculate age in years
        
        $address = $student['barangay'] . ', ' . $student['city'] . ', ' . $student['province'] . ', ' . $student['region'];

        $parentFullName = $student['emergency_first_name'] . ' ' . $student['emergency_last_name'];
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
                            <input name="grade-level" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly />
                        
                            </div>
                       
                    </div>

                    <div>
                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Academic Year</p>
                            <div class="relative flex items-center">
                            <input name="academic-year" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly  />
                        
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
                                        <input name="age" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly  value="<?php echo $age ?>" />
                                    
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
                                        <input name="birthday" type="date"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['date_of_birth']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>


                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Birthplace</p>
                                        <div class="relative flex items-center">
                                        <input name="birth-place" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['birth_place']); ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                                    <div class="col-span-2">
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Address</p>
                                        <div class="relative flex items-center">
                                        <input name="address" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo $address ?>"    />
                                    
                                        </div>
                                    
                                    </div>
                    </div>


                </div>


                <!-- Guardian Information -->

                <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Emergency Contact" />
                <div role="tabpanel" class="tab-content pt-14 px-4 space-y-6">

                    <h1 class="text-lg font-bold">Emergency Contact <span class="text-red-500">*</span></h1>
                  
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">

                    
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Full Name</p>
                                        <div class="relative flex items-center">
                                        <input name="guardian-full-name" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo  $parentFullName ?>"   />
                                    
                                        </div>
                                    
                                    </div>

                                    
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Contact No.</p>
                                        <div class="relative flex items-center">
                                        <input name="guardian_contact_number" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['emergency_number']); ?>"  />
                                    
                                        </div>
                                    
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Relationship to the Student</p>
                                        <div class="relative flex items-center">
                                        <input name="relationship" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-amber-50" readonly value="<?php echo htmlspecialchars($student['relationship']); ?>"   />
                                    
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
        <p>Download Document</p>

        <div class="tooltip tooltip-bottom" data-tip="Download Document">
            <!-- Change the button to a link for triggering the download -->
            <a href="?student_id=<?php echo $student_id; ?>&action=download" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            </a>
        </div>
    </div>
</div>


                </div>
                
            </div>






            
        </div>

    
    </div>


</body>
</html>