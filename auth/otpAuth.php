<?php
session_start();
ob_start(); // Start output buffering to prevent header issues
$response = null; // Variable to store the response status


include '../config/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable('../config');
$dotenv->load();


// Ensure OTP page is accessed only if OTP is generated in the previous step
if (!isset($_SESSION['otp_email'])) {
    header('Location: login.php'); // Redirect to login if no OTP email session
    exit();
}


if (isset($_POST['verifyOtp'])) {
    // Get OTP as an array
    $otpArray = $_POST['otp']; // This will be an array like ['0' => '1', '1' => '2', ...]

    // Combine the OTP array into a single string
    $otp = implode('', $otpArray); // This will join the OTP digits into a single string like "1234"

    $email = $_SESSION['otp_email']; // Get the email from session
    $name = $_SESSION['otp_name']; // Get the name from session
    $secret_key = $_ENV['SECRET_KEY']; // Get the secret key from environment variables


    // Check if OTP and email are valid
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Add Philipine timezone to OTP expiry
        date_default_timezone_set('Asia/Manila');
        // Check if OTP has expired
        if (strtotime($user['otp_expiry']) < time()) {
            $_SESSION['error'] = "OTP has expired. Please try again.";
            unset($_SESSION['otp_email']); // Clear OTP session variable
            header('Location: otpAuth.php'); // Redirect back to OTP page for retry
            exit();
        }

        // To check the OTP, we need to hash the OTP entered by the user using the secret key
        // $computed_hmac = hash_hmac('sha256', $otp, $secret_key);
        // echo "Computed HMAC: " . $computed_hmac . "<br>";
        // echo "Stored HMAC: " . $user['otp'] . "<br>";

        // if ($computed_hmac === $user['otp']) {
        //     echo "OTP Verified!";
        // } else {
        //     echo "Invalid OTP!";
        // }
        // exit();


        // Verify OTP entered by user with the OTP in the database
        if (hash_hmac('sha256', $otp, $secret_key) === $user['otp']) {

            // OTP is valid, mark as verified
            $_SESSION['otp_verified'] = true; // Mark OTP as verified
            $_SESSION['user_id'] = $user['user_id'];
            // Clear OTP session variable after successful validation
            unset($_SESSION['otp_email']);


            // Get current device info
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $device_hash = hash('sha256', $user_agent . $ip_address); // Unique device identifier

            // Check if this device is already registered
            $sql = "SELECT * FROM user_devices WHERE user_id = ? AND device_hash = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("is", $user['user_id'], $device_hash);
            $stmt->execute();
            $device_result = $stmt->get_result();

            
            if ($device_result->num_rows === 0) {
                // New device detected - Insert it into the database
                $sql = "INSERT INTO user_devices (user_id, device_hash, user_agent, ip_address) VALUES (?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("isss", $user['user_id'], $device_hash, $user_agent, $ip_address);
                $stmt->execute();

                // Log the event in audit logs
                $log_sql = "INSERT INTO audit_logs (user_id, action, resource_type, created_at) VALUES (?, 'New Device Login', 'Session', NOW())";
                $log_stmt = $connection->prepare($log_sql);
                $log_stmt->bind_param("i", $user['user_id']);
                $log_stmt->execute();

                 // Send alert (Optional: Email notification to user)
                $email = $_SESSION['otp_email']; // Get the email from session
                $name = $_SESSION['otp_name']; // Get the name from session
               
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sweetmiyagi@gmail.com';  
                    $mail->Password = 'euuy nadj ibmd acau'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS
                    $mail->addAddress($email); 

                    ob_start();
                    include 'newDeviceEmail_Template.php';  // Include the template
                    $emailBody = ob_get_clean();  // Get the output and store it in a variable

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'New Device Login Alert';
                    $mail->Body    = $emailBody;

                    $mail->send();

                } catch (Exception $e) {
                    $response = 'error'; // Error occurred
                }

            }

            // Log the login event in audit logs
            $sql = "INSERT INTO audit_logs (user_id, action, resource_type, created_at) VALUES (?, 'User Logged In', 'Session', NOW())";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('i', $user['user_id']);
            $stmt->execute();

            $sql = "UPDATE users SET otp = NULL, otp_expiry = NULL WHERE email = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();

            // Redirect to the appropriate dashboard based on role
            if ($user['role'] == 'admin') {
                header('Location: loading.php?target=../dist/admin/index.php');  // Admin dashboard
            } elseif ($user['role'] == 'teacher') {
                // Get the teacher_id corresponding to this user_id
                $teacher_query = "SELECT teacher_id FROM teachers WHERE user_id = ?";
                $stmt = $connection->prepare($teacher_query);
                $stmt->bind_param('i', $user['user_id']);
                $stmt->execute();
                $teacher_result = $stmt->get_result();
                
                if ($teacher_result->num_rows > 0) {
                    $teacher = $teacher_result->fetch_assoc();
                    // Pass the teacher_id (not user_id) as a parameter in the URL
                    header('Location: loading.php?target=../dist/teacher/index.php&teacher_id=' . $teacher['teacher_id']);
                } else {
                    // Fallback if teacher record not found
                    header('Location: loading.php?target=../dist/teacher/index.php');
                }
            } elseif ($user['role'] == 'student') {
                header('Location: loading.php?target=../dist/student/dashboard.php');  // Student dashboard
            } else {
                // Invalid role
                $response = 'invalidRole';
                $action = 'invalid';
            }
        } else {
            // OTP is incorrect
            $response = 'error';
        }
    } else {
        // User does not exist
        $response = 'error';
    }
}


if (isset($_POST['resendOtp'])) {

    if (isset($_SESSION['otp_resend_time']) && $_SESSION['otp_resend_time'] > time()) {

        $response = 'otpResendError';
        $action = 'reject';
    }

    $_SESSION['otp_resend_time'] = time() + 60;

    $email = $_POST['email'] ?? $_SESSION['otp_email'];
    $name = $_POST['name'] ?? $_SESSION['otp_name'];

    if (!$email || !$name) {
        $response = 'error'; // Error occurred
    }


    // Generate OTP
    $otp = rand(1000, 9999); // Generate OTP


    // Add Philipine timezone to OTP expiry
    date_default_timezone_set('Asia/Manila');
    $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes')); // OTP expiry time (5 minutes from now)

    // Encrypt OTP using a secret key
    $secret_key = $_ENV['SECRET_KEY'];
    $encrypted_otp = hash_hmac('sha256', $otp, $secret_key);

    // Store OTP hash in the database with an expiry time
    $sql = "UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sss', $encrypted_otp, $otp_expiry, $email);
    $stmt->execute();

    // Store the email in session to validate the OTP later
    $_SESSION['otp_email'] = $email;
    $_SESSION['otp_name'] = $name;

    // Send OTP to user via email using PHPMailer
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'sweetmiyagi@gmail.com';  // Your email (sender's email)
        $mail->Password = 'vbzj pxng toyc xmht';  // Your Gmail app password (not the student's email)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
        $mail->Port = 587;  // SMTP Port

        // Sender and recipient details
        $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');  // Sender's email
        $mail->addAddress($email);  // Recipient's email (the student's email from DB)


        ob_start();
        include 'otpEmail_Template.php';  // Include the template
        $emailBody = ob_get_clean();  // Get the output and store it in a variable

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Login';
        $mail->Body    = $emailBody;

        $mail->send();

        $response = 'success'; // OTP sent successfully
        $action = 'approve';
    } catch (Exception $e) {
        $response = 'error'; // Rejection failed
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
    <title>Authentication</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>
    <html data-theme="light">


    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>


    </html>


</head>

<body class="relative h-screen flex items-center justify-center bg-[#f2f5f8]">



    <a class="absolute top-8 left-4 px-4 flex items-center gap-2 btn btn-ghost text-blue-500" href="login.php">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>

        Go back to Login
    </a>

    <div class="relative max-w-2xl mx-auto text-center bg-white px-4 sm:px-8 py-10 rounded-xl shadow">
        <header class="mb-8">
            <h1 class="text-2xl font-extrabold mb-1">Enter OTP</h1>
            <p class="text-[15px] text-gray-700">An OTP has been sent to your email.</p>
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
                    maxlength="1" oninput="moveFocus(this, 'otp4')" />
                <input
                    type="text" name="otp[3]" id="otp4" required
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" />
            </div>
            <div class="max-w-[260px] mx-auto mt-4">
                <button
                    name="verifyOtp"
                    type="submit"
                    class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-blue-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-blue-950/10 hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-blue-300 transition-colors duration-150">Verify
                    OTP</button>
            </div>
        </form>
        <form id="resendOtpForm" action="" method="POST" class="text-sm text-slate-500 mt-4">
            Didn't receive the code?
            <button name="resendOtp" type="submit" id="resendBtn" class="font-medium text-blue-500 hover:text-blue-600">
                Resend
            </button>
        </form>

        <p id="resendTimer" class="font-medium mt-4"></p>

    </div>

    <div class="absolute bottom-4 px-4 w-full text-xs text-gray-400  flex items-center justify-between">
        <p class="hover:text-black transition-colors">© 2024 Lumix. All Rights Reserved.</p>
        <div class="flex items-center gap-6">
            <a href="" class="hover:text-black transition-colors">Privacy</a>
            <a href="" class="hover:text-black transition-colors">Cookies Policy</a>
        </div>
    </div>


    <!-- OTP FORM SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('otp-form')
            const inputs = [...form.querySelectorAll('input[type=text]')]
            const submit = form.querySelector('button[type=submit]')

            const handleKeyDown = (e) => {
                if (
                    !/^[0-9]{1}$/.test(e.key) &&
                    e.key !== 'Backspace' &&
                    e.key !== 'Delete' &&
                    e.key !== 'Tab' &&
                    !e.metaKey
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
                const {
                    target
                } = e
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


    <!-- OTP TIMER -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let resendTime = <?php echo isset($_SESSION['otp_resend_time']) ? $_SESSION['otp_resend_time'] - time() : 0; ?>;
            let resendBtn = document.getElementById("resendBtn");
            let resendTimer = document.getElementById("resendTimer");

            function updateTimer() {
                if (resendTime > 0) {
                    resendBtn.disabled = true;
                    resendBtn.style.color = "#a1a1aa";
                    resendTimer.textContent = `Resend available in ${resendTime}s`;
                    resendTime--;
                    setTimeout(updateTimer, 1000);
                } else {
                    resendBtn.disabled = false;
                    resendTimer.textContent = "You can now resend OTP.";
                }
            }

            if (resendTime > 0) {
                updateTimer();
            } else {
                resendBtn.disabled = false;
            }
        });
    </script>

    <!-- Notyf -->
    <script>
        // Initialize Notyf
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
            if (action === 'approve') {
                notyf.success('OTP has been resent successfully.');
            }

        } else if (status === 'otpResendError') {
            if (action === 'reject') {
                notyf.error('Please wait before requesting another OTP.');
            }

        } else if (status === 'invalidRole') {
            if (action === 'invalid') {
                notyf.error('Invalid role. Please try again.');
            }

        } else if (status === 'error') {
            notyf.error('An error occurred. Please try again.');
        }
    </script>

    <script>
        // Remove the 'status' query parameter after the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const url = new URL(window.location.href);
            if (url.searchParams.has('status')) {
                url.searchParams.delete('status'); // Remove the 'status' parameter
                window.history.replaceState({}, document.title, url.pathname); // Update the URL without reloading
            }
        });
    </script>


</body>

</html>