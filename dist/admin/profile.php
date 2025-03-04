<?php

session_start();

include '../../config/db.php';

$user_id = $_SESSION['user_id'];



$sql = "SELECT * from users where user_id = $user_id";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);



$full_name = $row['name'];
$emailAdd = $row['email'];

// echo result
// echo "<pre>";
// print_r($row);
// echo "</pre>";


// change password add check if current password is same on the database
if (isset($_POST['changePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Get the hashed password from the database
    $sql = "SELECT password FROM users WHERE user_id = $user_id";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];

        // Check if the entered current password matches the stored hashed password
        if (password_verify($currentPassword, $hashedPassword)) {
            // Proceed with the password change if new passwords match
            if ($newPassword == $confirmPassword) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $updateSql = "UPDATE users SET password = '$newHashedPassword' WHERE user_id = $user_id";
                $updateResult = mysqli_query($connection, $updateSql);

                if ($updateResult) {
                    $response = 'success';
                    $action = 'changePassword';
                } else {
                    $response = 'error';
                    $action = 'changePasswordError';
                }
            } else {

                $response = 'error';
                $action = 'doNotMatch';
            }
        } else {

            $response = 'error';
            $action = 'incorrectPassword';
        }
    } else {

        $response = 'error';
        $action = 'userNotFound';
    }

    // Redirect with JavaScript
    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?status=$response&action=$action';</script>";
    exit;
}


// update info
if (isset($_POST['updateInfo'])) {
    $email = $_POST['email'];

    $stmt = $connection->prepare("UPDATE students SET email = ? WHERE user_id = ?");
    $stmt->bind_param("si", $email, $user_id);
    $result = $stmt->execute();

    if ($result) {
        $response = 'success';
        $action = 'updateInfo';
    } else {
        $response = 'error';
        $action = 'updateInfoError';
    }

    // Redirect with JavaScript
    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?status=$response&action=$action';</script>";
    exit;
}







?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

    <html data-theme="light">

    </html>

</head>

<body class="flex min-h-screen">


    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

        <!-- Navbar -->

        <?php include('./components/navbar.php'); ?>


        <div class="p-6">




            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li>Profile</li>
                    
                </ul>
            </div>


            <div class="bg-white rounded-md mt-7 flex flex-col xl:flex-row gap-7">


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

                        <div class=" px-7 py-6 bg-blue-50">
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
                                                class="bg-blue-500 rounded w-4 h-4 p-1">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="mt-4">

                                        <p class="font-medium">Profile photo</p>
                                        <p class="font-medium text-gray-400 text-sm">This will be displayed on your profile.</p>
                                    </div>




                                </div>

                                <span class="inline-flex items-center text-sm rounded-md bg-gray-50 px-2 py-1 font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 ">Current enrolled in Grade 1</span>

                            </div>



                            <form action="" class="space-y-6" method="POST">

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Name</label>
                                        <div class="relative flex items-center">
                                            <input name="" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly value="<?php echo $full_name  ?>" />

                                        </div>
                                    </div>

                               

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Email Address</label>
                                        <div class="relative flex items-center">
                                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo $emailAdd ?>" />

                                        </div>
                                    </div>

                                    

                                 



                                </div>



                                <div class=" flex items-center justify-end">
                                    <button name="updateInfo" class=" py-3 px-14 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Update</button>
                                </div>

                            </form>








                        </div>

                    </section>

                    <!-- Notifications -->
                    <section class="border-gray-100 border rounded" id="notifications">
                        <div class=" px-7 py-6 bg-blue-50">
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
                        <div class=" px-7 py-6 bg-blue-50">
                            <p class="text-lg font-medium">Password & Security</p>
                        </div>

                        <div class=" px-7 py-6  space-y-12">


                            <form action="" class="space-y-6" method="POST">

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Enter Current Password</label>
                                        <div class="relative flex items-center">
                                            <input name="currentPassword" id="currentPassword" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                            <button type="button" class="absolute right-2 top-2" onclick="togglePassword('currentPassword', 'eyeIcon1')">
                                                <i id="eyeIcon1" class="bx bx-hide text-gray-500"></i>
                                            </button>
                                        </div>
                                    </div>



                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Enter new Password</label>
                                        <div class="relative flex items-center">
                                            <input name="newPassword" id="newPassword" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                            <button type="button" class="absolute right-2 top-2" onclick="togglePassword('newPassword', 'eyeIcon2')">
                                                <i id="eyeIcon2" class="bx bx-hide text-gray-500"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium mb-2 block">Confirm new Password</label>
                                        <div class="relative flex items-center">
                                            <input name="confirmPassword" id="confirmPassword" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                                            <button type="button" class="absolute right-2 top-2" onclick="togglePassword('confirmPassword', 'eyeIcon3')">
                                                <i id="eyeIcon3" class="bx bx-hide text-gray-500"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>



                                <div class=" flex items-center justify-end">
                                    <button type="submit" name="changePassword" class=" py-3 px-14 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Update</button>
                                </div>

                            </form>








                        </div>

                    </section>

                </div>


            </div>



        </div>


    </div>




</body>

</html>


<script>
    function togglePassword(inputId, eyeIconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(eyeIconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bx-hide", "bx-show");
        } else {
            input.type = "password";
            icon.classList.replace("bx-show", "bx-hide");
        }
    }
</script>

<script>
    const notyf = new Notyf({
        duration: 3000, // Duration of the notification (3 seconds)
        position: {
            x: 'right', // Align notifications to the right
            y: 'top' // Show notifications at the top
        }
    });

    // Check for `status` and `action` query parameters in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const action = urlParams.get('action');

    if (status === 'success') {
        if (action === 'changePassword') {
            notyf.success('Password has been changed');
        } else if (action === 'updateInfo') {
            notyf.success('Information has been updated');
        }
    } else if (status === 'error') {
        if (action === 'doNotMatch') {
            notyf.error('New password and confirm password do not match');
        } else if (action === 'incorrectPassword') {
            notyf.error('Current password is incorrect');
        } else if (action === 'changePasswordError') {
            notyf.error('Failed to change password');
        } else if (action === 'userNotFound') {
            notyf.error('User not found');
        } else if (action === 'updateInfoError') {
            notyf.error('Failed to update information');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const url = new URL(window.location.href);
        if (url.searchParams.has('status')) {
            url.searchParams.delete('status'); // Remove the 'status' parameter
            window.history.replaceState({}, document.title, url.pathname); // Update the URL without reloading
        }
    });
</script>