<?php
session_start();
include '../config/db.php';

// Ensure OTP page is accessed only if OTP is generated in the previous step
if (!isset($_SESSION['otp_email'])) {
    header('Location: login.php'); // Redirect to login if no OTP email session
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get OTP as an array
    $otpArray = $_POST['otp']; // This will be an array like ['0' => '1', '1' => '2', ...]

    // Combine the OTP array into a single string
    $otp = implode('', $otpArray); // This will join the OTP digits into a single string like "1234"

    $email = $_SESSION['otp_email']; // Get the email from session

    // Check if OTP and email are valid
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify OTP
        if ($otp === $user['otp']) {
            // Check if OTP has expired
            if (strtotime($user['otp_expiry']) < time()) {
                $_SESSION['error'] = "OTP has expired. Please try again.";
                unset($_SESSION['otp_email']); // Clear OTP session variable
                header('Location: otpAuth.php'); // Redirect back to OTP page for retry
                exit();
            }

            // OTP is valid, mark as verified
            $_SESSION['otp_verified'] = true; // Mark OTP as verified

            // Clear OTP session variable after successful validation
            unset($_SESSION['otp_email']);

            // Redirect to the appropriate dashboard based on role
            if ($user['role'] == 'admin') {
                header('Location: ../dist/admin/index.php');  // Admin dashboard
            } elseif ($user['role'] == 'student') {
                header('Location: ../dist/student/dashboard.php');  // Student dashboard
            } else {
                $_SESSION['error'] = "Invalid role. Access denied.";
                header('Location: otpAuth.php'); // Redirect back to OTP page for retry
                exit();
            }
        } else {
            // OTP is incorrect
            $_SESSION['error'] = "Invalid OTP. Please try again.";
            header('Location: otpAuth.php'); // Redirect back to OTP page for retry
            exit();
        }
    } else {
        // User does not exist
        $_SESSION['error'] = "No user found with this email.";
        header('Location: otpAuth.php'); // Redirect back to OTP page for retry
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Authentication</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>
    <html data-theme="light"></html>


</head>
<body class="relative h-screen flex items-center justify-center bg-stone-50">

    <a class="absolute top-8 left-4 px-4 flex items-center gap-2 btn btn-ghost text-blue-500" href="login.php">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>

        Go back to Login
   </a>

    <div class="relative max-w-md mx-auto text-center bg-white px-4 sm:px-8 py-10 rounded-xl shadow">
        <header class="mb-8">
            <h1 class="text-2xl font-bold mb-1">OTP Authentication</h1>
            <p class="text-[15px] text-slate-500">Please enter the OTP sent to your email.</p>
        </header>
        <form action="otpAuth.php" method="POST">
            <div class="flex items-center justify-center gap-3">
                <input
                    type="text" name="otp[0]" id="otp1" required
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    pattern="\d*" maxlength="1" oninput="moveFocus(this, 'otp2')" autofocus /> 
                <input
                    type="text" name="otp[1]" id="otp2" required
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" oninput="moveFocus(this, 'otp3')" />
                <input
                    type="text" name="otp[2]" id="otp3" required
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" oninput="moveFocus(this, 'otp4')"/>
                <input
                    type="text" name="otp[3]" id="otp4" required
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" />
            </div>
            <div class="max-w-[260px] mx-auto mt-4">
                <button type="submit"
                    class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-blue-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-blue-950/10 hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-blue-300 transition-colors duration-150">Verify
                    OTP</button>
            </div>
        </form>
        <div class="text-sm text-slate-500 mt-4">Didn't receive code? <a class="font-medium text-blue-500 hover:text-blue-600" href="#0">Resend</a></div>
    </div>

    <div class="absolute bottom-4 px-4 w-full text-xs text-gray-400  flex items-center justify-between">
            <p class="hover:text-black transition-colors">© 2024 Lumix. All Rights Reserved.</p>
            <div class="flex items-center gap-6">
                <a href="" class="hover:text-black transition-colors">Privacy</a>
                <a href="" class="hover:text-black transition-colors">Cookies Policy</a>
            </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('otp-form')
        const inputs = [...form.querySelectorAll('input[type=text]')]
        const submit = form.querySelector('button[type=submit]')

        const handleKeyDown = (e) => {
            if (
                !/^[0-9]{1}$/.test(e.key)
                && e.key !== 'Backspace'
                && e.key !== 'Delete'
                && e.key !== 'Tab'
                && !e.metaKey
            ) {
                e.preventDefault()
            }

            if (e.key === 'Delete' || e.key === 'Backspace') {
                const index = inputs.indexOf(e.target);
                if (index > 0) {
                    inputs[index - 1].value = '';
                    inputs[index - 1].focus();
                }
            }
        }

        const handleInput = (e) => {
            const { target } = e
            const index = inputs.indexOf(target)
            if (target.value) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus()
                } else {
                    submit.focus()
                }
            }
        }

        const handleFocus = (e) => {
            e.target.select()
        }

        const handlePaste = (e) => {
            e.preventDefault()
            const text = e.clipboardData.getData('text')
            if (!new RegExp(`^[0-9]{${inputs.length}}$`).test(text)) {
                return
            }
            const digits = text.split('')
            inputs.forEach((input, index) => input.value = digits[index])
            submit.focus()
        }

        inputs.forEach((input) => {
            input.addEventListener('input', handleInput)
            input.addEventListener('keydown', handleKeyDown)
            input.addEventListener('focus', handleFocus)
            input.addEventListener('paste', handlePaste)
        })
    })          
    
    function moveFocus(currentInput, nextInputId) {
        if (currentInput.value.length === 1) {
            const nextInput = document.getElementById(nextInputId);
            if (nextInput) {
                nextInput.focus();
            }
        }
    }
</script>
    
</body>
</html>