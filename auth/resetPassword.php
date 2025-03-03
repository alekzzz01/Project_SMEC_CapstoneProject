<?php

session_start();
include '../config/db.php';
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../config');
$dotenv->load();

// check if email and token is not set
if (!isset($_GET['email']) || !isset($_GET['token'])) {
    header('Location: login.php');
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $email = $_GET['email'];
    $token = $_GET['token'];

    $recaptcha_response = $_POST['g-recaptcha-response']; // Get reCAPTCHA response

    // Step 1: Verify reCAPTCHA
    $secretKey = $_ENV['RECAPTCHA_SECRET'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($url . '?secret=' . $secretKey . '&response=' . $recaptcha_response . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        $_SESSION['error'] = "reCAPTCHA verification failed. Please try again.";
        header("Location: resetPassword.php?email=$email&token=$token");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match';
    } else {
        $email = $_GET['email'];
        $token = $_GET['token'];

        $sql = "SELECT * FROM users WHERE email = ? AND token = ? AND token_expiry > NOW()";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = ?, token = NULL, token_expiry = NULL WHERE email = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ss', $password, $email);
            $stmt->execute();

            $_SESSION['success'] = 'Password reset successful';
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid or expired token. Please try to reset your password again or contact IT support.';
            header("Location: resetPassword.php?email=$email&token=$token");
            exit();
        }
    }
}




?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SMEC</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>
    <html data-theme="light">


    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>



</head>



<body class="h-screen relative">



    <div class="grid grid-cols-1 xl:grid-cols-2 h-full gap-2">

        <div class="flex flex-col  justify-between p-4 space-y-12">

            <div class="flex flex-col w-full lg:w-[456px] m-auto ">

                <div class="flex flex-col items-start ">

                    <div class="flex items-center justify-between w-full">
                        <a class="px-4 flex gap-2 btn btn-ghost border border-gray-300" href="login.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>

                            Login
                        </a>


                        <div class="dropdown dropdown-hover dropdown-top dropdown-end">
                            <div tabindex="0" role="button" class="px-4 flex gap-2 btn btn-ghost border border-gray-300 mt-1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                </svg>
                            </div>
                            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-80 p-2 shadow">
                                <li>✅ At least (16 characters)</li>
                                <li>✅ Use uppercase & lowercase letters</li>
                                <li>✅ Include numbers (0-9)</li>
                                <li>✅ Add special characters (@, #, $, etc.)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-2 mt-8">
                        <h5 class="text-xl lg:text-2xl font-bold">
                            Reset Password
                        </h5>
                        <p class="text-slate-500">Please enter your new password below.</p>
                    </div>


                </div>

                <form method="POST" class="mt-8 space-y-4 w-full" x-data="app()">

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">New Password</label>
                        <div class="relative flex items-center">
                            <input id="password" name="password" type="password" x-model="password" @input="checkStrength" required class="bg-gray-100 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                            <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Confirm Password</label>
                        <div class="relative flex items-center">
                            <input id="confirmPassword" name="confirmPassword" type="password" required class="bg-gray-100 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Confirm password" />
                            <button type="button" onclick="togglePassword('confirmPassword', 'toggleConfirmPasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                <i id="toggleConfirmPasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                            </button>
                        </div>
                    </div>



                    <div class="flex -mx-1 mt-2">
                        <template x-for="(v, i) in 5" :key="i">
                            <div class="w-1/5 px-1">
                                <div class="h-2 rounded-xl transition-colors"
                                    :class="i < passwordScore ? 
                                        (passwordScore >= 4 ? 'bg-green-500' : 
                                        passwordScore === 3 ? 'bg-yellow-400' : 
                                        'bg-red-400') : 'bg-gray-200'">
                                </div>
                            </div>
                        </template>
                    </div>

                    <p x-text="passwordScore === 0 ? ' ' : 
                            passwordScore <= 2 ? 'Weak 🔴' : 
                            passwordScore === 3 ? 'Medium 🟡' : 
                            passwordScore >= 4 ? 'Strong 🟢' : ''"
                        class="text-end">
                    </p>



                    <div class="!mt-8">
                        <button type="submit"
                            class="w-full font-medium py-3 px-4 text-sm tracking-wide rounded-lg text-white 
                        bg-blue-600 hover:bg-blue-700 focus:outline-none disabled:bg-gray-400 disabled:cursor-not-allowed"
                            :disabled="passwordScore < 4">
                            Reset Password
                        </button>
                    </div>



                    <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->

                    <!-- Google Recaptcha -->
                    <div class="flex items-center justify-center">
                        <div class="g-recaptcha" data-sitekey="6LfIZ5EqAAAAAGeXLXbd-FE6FjKxV-VKz4wfSLM2"></div>
                    </div>
                </form>

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
            <img src="../assets/images/forgotPassword.gif" alt="" class=" h-1/2 w-full">
        </div>





    </div>






</body>

</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>


<script>
    function app() {

        let strengthText = document.getElementById("strengthText");

        return {
            showPasswordField: true,
            passwordScore: 0,
            password: '',
            chars: {
                lower: 'abcdefghijklmnopqrstuvwxyz',
                upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                numeric: '0123456789',
                symbols: '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~'
            },
            charsLength: 12,
            checkStrength: function() {
                if (!this.password) {
                    this.passwordScore = 0;
                    return;
                }

                let score = 0;

                // Check for minimum length
                if (this.password.length >= 8) {
                    score++;
                }

                // Check for lowercase letters
                if (/[a-z]/.test(this.password)) {
                    score++;
                }

                // Check for uppercase letters
                if (/[A-Z]/.test(this.password)) {
                    score++;
                }

                // Check for numbers
                if (/\d/.test(this.password)) {
                    score++;
                }

                // Check for special characters
                if (/[!@#$%^&*]/.test(this.password)) {
                    score++;
                }

                this.passwordScore = score;
            },



        }
    }
</script>