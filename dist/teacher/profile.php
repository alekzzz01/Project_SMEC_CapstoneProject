<?php
session_start();

// Get teacher_id from session or URL parameter
if (isset($_GET['teacher_id'])) {
    $_SESSION['teacher_id'] = $_GET['teacher_id'];
}

$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : 
              (isset($_GET['teacher_id']) ? htmlspecialchars($_GET['teacher_id']) : '');

// If no teacher_id, redirect to login
if (empty($teacher_id)) {
    header("Location: ../login.php");
    exit;
}

// Database connection
include '../../config/db.php'; // Adjust path as needed

// Fetch teacher information
// Fetch teacher information with email from users table
$sql = "SELECT t.teacher_id, t.user_id, t.First_Name, t.Last_Name, t.Date_of_Birth, t.gender, t.Contact_Number, 
               u.email, t.region, t.province, t.city, t.barangay, t.zip_code
        FROM teachers t
        JOIN users u ON t.user_id = u.user_id
        WHERE t.teacher_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $teacher_info = $result->fetch_assoc();
} else {
    // Handle case when teacher not found
    header("Location: ../login.php");
    exit;
}

// Handle form submission for basic info update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_info'])) {
    $email = $_POST['email'];
    
    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    } else {
        // Update the email in database
        $update_sql = "UPDATE teachers SET email = ? WHERE teacher_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $email, $teacher_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully";
            // Refresh teacher info
            $stmt->execute();
            $result = $stmt->get_result();
            $teacher_info = $result->fetch_assoc();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   
</head>
<body class="bg-[#f7f7f7] min-h-screen">


    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12">


   
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li>Profile</li>
            <li>Basic Information</li>
        </ul>
        </div>


    <div class="p-7 bg-white rounded-md mt-7 flex flex-col xl:flex-row gap-7">


        <!-- Navigation -->
        <div class="space-y-4 col-span-1 max-w-72 w-full">

            

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                    </svg>

                    <a href="#basicinfo">Basic Information</a>
                </div>

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z" clip-rule="evenodd" />
                    </svg>


                    <a href="#notifications">Notifications</a>
                </div>

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>

                    <a href="#password">Password and Security</a>
                </div>



            
        </div>

        <!-- Contents -->
        <div class="col-span-2 flex flex-col gap-7 w-full">

            <!-- Basic Information -->
            <section class="border-gray-100 border rounded" id="basicinfo">

                <div class=" px-7 py-6 bg-teal-50">
                    <p class="text-lg font-medium">Basic Information</p>
             
                    
                </div>

                <div class=" px-7 py-6  space-y-12">

                    <div class="flex flex-wrap items-center justify-between gap-7">

                        <div class="flex items-center gap-7">

                            <div class="relative w-[80px] h-[80px]">
                                <!-- Profile Image -->
                                <img class="rounded-full w-full h-full" 
                                    src="https://static.vecteezy.com/system/resources/previews/001/840/612/non_2x/picture-profile-icon-male-icon-human-or-people-sign-and-symbol-free-vector.jpg" 
                                    alt="Profile Picture">

                                <!-- Icon -->
                                <div class="absolute bottom-2 right-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" 
                                        class="bg-teal-500 rounded w-4 h-4 p-1">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="mt-4">
                            
                                <p class="font-medium">Profile photo</p>
                                <p class="font-medium text-gray-400 text-sm">This will be displayed on your profile.</p>
                            </div>

               


                        </div>

                    

                    </div>


            
                   <form method="POST" action="" class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">
                            
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Name</label>
                            <div class="relative flex items-center">
                            <input name="name" type="text" value="<?php echo htmlspecialchars($teacher_info['First_Name'] . ' ' . $teacher_info['Last_Name']); ?>" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" readonly/>
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Email Address</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" value="<?php echo htmlspecialchars($teacher_info['email']); ?>" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" />
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Gender</label>
                            <div class="relative flex items-center">
                            <input name="gender" type="text" value="<?php echo htmlspecialchars($teacher_info['gender']); ?>" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" readonly />
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Date of Birth</label>
                            <div class="relative flex items-center">
                            <input name="dob" type="date" value="<?php echo isset($teacher_info['Date_of_Birth']) ? htmlspecialchars($teacher_info['Date_of_Birth']) : ''; ?>" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" readonly />
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Address</label>
                            <div class="relative flex items-center">
                            <input name="address" type="text" 
                                value="<?php 
                                        $address_parts = [];
                                        if (!empty($teacher_info['region'])) $address_parts[] = $teacher_info['region'];
                                        if (!empty($teacher_info['province'])) $address_parts[] = $teacher_info['province'];
                                        if (!empty($teacher_info['city'])) $address_parts[] = $teacher_info['city'];
                                        if (!empty($teacher_info['barangay'])) $address_parts[] = $teacher_info['barangay'];
                                        if (!empty($teacher_info['zip_code'])) $address_parts[] = $teacher_info['zip_code'];
                                        echo htmlspecialchars(implode(', ', $address_parts));
                                ?>" 
                                class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" readonly />
                            </div>
                        </div>


                                    

                                
                        </div>



                        <div class="flex items-center justify-end">
                            <button type="submit" name="update_info" class="py-3 px-14 text-sm rounded-md text-white font-medium tracking-wide bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2 focus:ring-offset-teal-50 transition-colors group">Update</button>
                        </div>

                    </form>

                  
                     
               
                    



                </div>

            </section>

            <!-- Notifications -->
            <section class="border-gray-100 border rounded" id="notifications">
                <div class=" px-7 py-6 bg-teal-50">
                    <p class="text-lg font-medium">Notifications</p>
                </div>


                <div class=" px-7 py-6 flex flex-col xl:flex-row justify-between gap-12">

                    <div class="space-y-7">
                        <div>
                            <p class="text-lg font-medium">Email notification</p>
                            <p class="text-gray-400 text-sm">Get emails to find out what”s going on when
                            you”re not online. You can turn these off.</p>
                        </div>

                        <div class="space-y-5">
                            <div class="flex gap-5">
                                <input type="checkbox" class="toggle" checked="checked" />
                                <div>
                                    <p class="font-medium">Reminders</p>
                                    <p class="text-gray-400 text-sm">These are notification to remind you of 
                                    updates you might have missed.</p>

                                </div>
                            </div>

                            <div class="flex gap-5">
                                <input type="checkbox" class="toggle" checked="checked" />
                                <div>
                                    <p class="font-medium">Reminders</p>
                                    <p class="text-gray-400 text-sm">These are notification to remind you of 
                                    updates you might have missed.</p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-b xl:border-r border-gray-100"></div>

                    <div class="space-y-7">
                        <div>
                            <p class="text-lg font-medium">Push notification</p>
                            <p class="text-gray-400 text-sm">Get emails to find out what”s going on when
                            you”re not online. You can turn these off.</p>
                        </div>

                        <div class="space-y-5">
                            <div class="flex gap-5">
                                <input type="checkbox" class="toggle" checked="checked" />
                                <div>
                                    <p class="font-medium">Reminders</p>
                                    <p class="text-gray-400 text-sm">These are notification to remind you of 
                                    updates you might have missed.</p>

                                </div>
                            </div>

                            <div class="flex gap-5">
                                <input type="checkbox" class="toggle" checked="checked" />
                                <div>
                                    <p class="font-medium">Reminders</p>
                                    <p class="text-gray-400 text-sm">These are notification to remind you of 
                                    updates you might have missed.</p>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>



            </section>

            <!-- Password and Security -->
            <section class="border-gray-100 border rounded" id="password">
                <div class=" px-7 py-6 bg-teal-50">
                    <p class="text-lg font-medium">Password & Security</p>
                </div>

                <div class=" px-7 py-6  space-y-12">

                
                    <form action="" class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">
                            
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Enter Current Password</label>
                                        <div class="relative flex items-center">
                                        <input name="name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" readonly/>
                                    
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Enter new Password</label>
                                        <div class="relative flex items-center">
                                        <input name="name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" readonly/>
                                    
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Confirm new Password</label>
                                        <div class="relative flex items-center">
                                        <input name="email" type="text" value="<?php echo isset($teacher_info['email']) ? htmlspecialchars($teacher_info['email']) : ''; ?>" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-teal-600" />
                                    
                                        </div>
                                    </div>
                       
                        </div>



                        <div class=" flex items-center justify-end">
                            <button class=" py-3 px-14 text-sm rounded-md text-white font-medium tracking-wide bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2 focus:ring-offset-green-50 transition-colors group">Update</button>
                        </div>

                    </form>


                    





                    </div>

            </section>

        </div>
       

    </div>



    </div>

    
</body>
</html>