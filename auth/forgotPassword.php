<?php

session_start();
include '../config/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable('../config');
$dotenv->load();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();

    if (!$user) {
        $_SESSION['error'] = 'User not found';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    $recaptcha_response = $_POST['g-recaptcha-response']; // Get reCAPTCHA response

    // Step 1: Verify reCAPTCHA
    $secretKey = $_ENV['RECAPTCHA_SECRET'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($url . '?secret=' . $secretKey . '&response=' . $recaptcha_response . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        $_SESSION['error'] = "reCAPTCHA verification failed. Please try again.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // check if user is lockout 
    if ($user['lockout'] == 1) {
        $_SESSION['error'] = 'Account is locked out. Please contact the administrator';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }


    if ($user) {
        $token = bin2hex(random_bytes(50));


        date_default_timezone_set('Asia/Manila');
        $token_expiry = date("Y-m-d H:i:s", strtotime('+30 minutes'));
        $sql = "UPDATE users SET token = ?, token_expiry = ? WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $token, $token_expiry, $email);
        $stmt->execute();
        $stmt->close();

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'sweetmiyagi@gmail.com';
            $mail->Password = 'euuy nadj ibmd acau';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
            $mail->Port = 587;  // SMTP Port

            //Recipients
            $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');
            $mail->addAddress($email, $user['name']);

            $reset_link = "http://localhost/dashboard/Projects/Project_SMEC_CapstoneProject/auth/resetPassword.php?email=" . urlencode($email) . "&token=" . urlencode($token);

            $name = $user['name'];

            ob_start();
            include 'forgotPasswordEmail_Template.php';  // Include the template
            $emailBody = ob_get_clean();  // Get the output and store it in a variable

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body    = $emailBody;

            $mail->send();
            $_SESSION['success'] = 'Reset link sent to your email';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error'] = 'User not found';
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
    <title>Forgot Password - SMEC</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>
    <html data-theme="light">


</head>



<body class="h-screen relative">




    <div class="grid grid-cols-1 xl:grid-cols-2 h-full gap-2">

        <div class="flex flex-col justify-between p-4 space-y-12">

            <div class="m-auto flex flex-col  w-full lg:w-[456px]">


                <div class="flex flex-col items-start">

                    <a class="px-4 flex gap-2 btn btn-ghost border border-gray-300" href="login.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>

                        Login
                    </a>

                    <div class="space-y-2 mt-8">
                        <h5 class="text-2xl lg:text-3xl font-bold text-start">
                            Forgot Password

                        </h5>
                        <p class="text-base-content/70 text-start">No Worries! Enter your email address below, and we’ll send you a link to reset your password</p>

                    </div>


                </div>

                <form method="POST" class="mt-8 space-y-4 w-full">
                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Email</label>
                        <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full bg-gray-100 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" />

                        </div>
                    </div>


                    <div class="!mt-8">
                        <button type="submit" class="w-full font-medium py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Request Reset Link
                        </button>
                    </div>
                    <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->

                    <!-- Google Recaptcha -->
                    <div class="flex items-center justify-center mt-8">
                        <div class="g-recaptcha" data-sitekey="6LfIZ5EqAAAAAGeXLXbd-FE6FjKxV-VKz4wfSLM2"></div>
                    </div>


                </form>



                <?php if (isset($_SESSION['success'])): ?>
                    <div class="text-sm mt-8 text-center px-4 py-3 bg-teal-100 border border-teal-400 text-teal-700"><?= $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>



                <?php if (isset($_SESSION['error'])): ?>
                    <div class="text-sm mt-8 text-center px-4 py-3 bg-red-100 border border-red-400 text-red-700"><?= $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
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
            <img src="../assets/images/forgotPassword1.gif" alt="" class=" h-1/2 w-full">
        </div>





    </div>






</body>

</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>