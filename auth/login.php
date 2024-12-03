<?php
session_start();

include '../config/db.php';

require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('../config');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
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

  
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role']; 

            // Redirect based on user role
            if ($_SESSION['user_role'] == 'admin') {
                header('Location: ../dist/admin/');
            } elseif ($_SESSION['user_role'] == 'teacher') {
                header('Location: ../dist/teacher/dashboard.php');
            } elseif ($_SESSION['user_role'] == 'student') {
                header('Location: ../dist/student/dashboard.php');
            }

            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "No user found with this username or invalid password.";
    }

    // Redirect back to the login page to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

    // Retrieve error message from session (if any)
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMEC</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>


</head>



<body class="h-screen">
    


    <div class="grid grid-cols-1 xl:grid-cols-2 h-full gap-2">

    <div class="flex flex-col  justify-between p-4 space-y-12">

        
        <a class="flex items-center gap-4" href="../">
       
            <img src="../assets/images/logo.png" alt="" class="w-10 h-10 object-cover">
         
        </a>

        <div class="m-auto flex flex-col items-center">
            <div class="space-y-3">
            <h5 class="text-2xl font-bold text-center">Welcome back to Sta. Marta Educational Inc.</h5>
            <p class="text-slate-500 text-center">Enter your email and password to continue</p>
            </div>
    
            <form method="POST" class="mt-8 space-y-4 w-full lg:w-[580px] ">
                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Email</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" />
                        
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 mx-3">
                            <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-slate-900/10 rounded" />
                            <label for="remember-me" class="ml-3 block text-sm text-gray-800">
                                Remember me
                            </label>
                            </div>
                            <div class="text-sm">
                            <a href="jajvascript:void(0);" class="text-blue-600 hover:underline font-semibold">
                                Forgot your password?
                            </a>
                            </div>
                        </div>

                   

                        <div class="!mt-8">
                            <button type="submit" class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Sign in
                            </button>
                        </div>
                        <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->
                        
                        <!-- Google Recaptcha -->
                        <div class="flex items-center justify-center">
                            <div class="g-recaptcha" data-sitekey="6LfIZ5EqAAAAAGeXLXbd-FE6FjKxV-VKz4wfSLM2"></div>
                        </div>
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


    <div class="w-full h-full hidden xl:grid justify-center items-center bg-blue-100">
                <img src="../assets/images/techny-school-supplies-for-school-subjects.gif" alt="" class=" h-1/2 w-full">
    </div>





    </div>





    
</body>
</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>