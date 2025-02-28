<?php
session_start();
include '../config/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable('../config');
$dotenv->load();



$sql = "SELECT * FROM customization_table WHERE theme_id = 1";
$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$customization = $result->fetch_assoc();


// Check if the user is already logged in (to avoid unnecessary redirects)
if (isset($_SESSION['user_id'])) {
    // If already logged in, redirect to the appropriate page based on role
    if ($_SESSION['role'] == 'admin') {
        header('Location: ../dist/admin/index.php'); // Admin redirect
    } else if ($_SESSION['role'] == 'teacher') {
        // Redirect to teacher dashboard with teacher_id parameter
        header('Location: ../dist/teacher/index.php?teacher_id=' . $_SESSION['teacher_id']);
    } else {

        header('Location: ../dist/student/dashboard.php'); // Student redirect
    } 
    exit(); // Ensure no further code is executed
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
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

    // Step 2: Validate email and password
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Step 3: Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $name = $user['name'];

        // Verify password
        // After successful password verification and before redirecting:
    if (password_verify($password, $user['password'])) {
    // Existing OTP generation code...
    
    // If the user is a teacher, get their teacher_id
    if ($user['role'] == 'teacher') {
        // Query to get teacher_id based on user_id
        $teacher_query = "SELECT teacher_id FROM teachers WHERE user_id = ?";
        $teacher_stmt = $connection->prepare($teacher_query);
        $teacher_stmt->bind_param('i', $user['user_id']);
        $teacher_stmt->execute();
        $teacher_result = $teacher_stmt->get_result();
        
        if ($teacher_result->num_rows > 0) {
            $teacher_data = $teacher_result->fetch_assoc();
            $_SESSION['teacher_id'] = $teacher_data['teacher_id']; // Store in session
        }
    }
    
            // Generate OTP
            $otp = rand(1000, 9999); // 4-digit OTP

            // Add Philipine timezone to OTP expiry
            date_default_timezone_set('Asia/Manila');
            $otp_expiry = date('Y-m-d H:i:s', strtotime('+1 minutes')); // OTP expiry time (5 minutes from now)

            // Encrypt OTP using a secret key
            $secret_key = $_ENV['SECRET_KEY'];
            $encrypted_otp = hash_hmac('sha256', $otp, $secret_key);

            // Store OTP hash in the database with an expiry time
            $sql = "UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $encrypted_otp, $otp_expiry, $email);
            $stmt->execute();

            // Previous Code
            // $encrypted_otp = password_hash($otp, PASSWORD_DEFAULT); // Encrypt OTP before storing in DB
            // $sql = "UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?";
            // $stmt = $connection->prepare($sql);
            // $stmt->bind_param('sss', $otp, $otp_expiry, $email);
            // $stmt->execute();


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


                // Redirect to OTP verification page
                header('Location: loading.php?target=otpAuth.php');
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "OTP could not be sent. Please try again later.";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with this email.";
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

                <!-- <img src="../assets/images/smeclogo.png" alt="" class="w-10 h-10 object-cover"> -->

                <?php
                // Check if the customization array is set and contains a school_logo key
                if (isset($customization['school_logo']) && !empty($customization['school_logo'])) {
                    echo '<img src="' . htmlspecialchars('../dist/admin/' . $customization['school_logo'], ENT_QUOTES, 'UTF-8') . '" class="w-10 h-10 object-cover bg-white rounded-full">';
                } else {
                    // Display a default logo if school_logo is not set or empty
                    echo '<img src="./../assets/images/defaultLogo.png" alt="Default Logo" class="w-10 h-10 object-cover bg-white rounded-full">';
                }
                ?>



            </a>

            <div class="m-auto flex flex-col items-center lg:w-[580px]">
                <div class="space-y-3">
                    <h5 class="text-3xl lg:text-4xl font-bold text-center bg-red">Welcome back to
                        <?php
                        if (isset($customization['school_name']) && !empty($customization['school_name'])) {
                            echo '<span class="text-3xl lg:text-4xl text-blue-800 font-bold text-center">' . htmlspecialchars($customization['school_name'], ENT_QUOTES, 'UTF-8') . '</span>';
                        } else {
                            echo '<span class="text-3xl lg:text-4xl text-blue-800 font-bold text-center">LUMIX - MIS</span>';
                        }
                        ?>

                    </h5>
                    <p class="text-slate-500 text-center">Enter your email and password to continue</p>
                </div>

                <form method="POST" class="mt-8 space-y-4 w-full">
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




                <?php if (isset($_SESSION['error'])): ?>
                    <div class="text-red-500 text-sm mt-8 text-center"><?= $_SESSION['error']; ?></div>
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
            <img src="../assets/images/techny-school-supplies-for-school-subjects.gif" alt="" class=" h-1/2 w-full">
        </div>





    </div>






</body>

</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>