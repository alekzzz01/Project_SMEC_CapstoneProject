<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../../config/db.php';
require '../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $connection = new mysqli($hostName, $dbAdmin, $dbPassword, $dbName);

    if ($connection->connect_error) {
        die("Database connection failed: " . $connection->connect_error);
    }

    // Sanitize input
    $student_number = htmlspecialchars($_POST['student_number']);
    $email = htmlspecialchars($_POST['email']);

    // Query to validate student_number in `students` and email/last_name in `admission_form`
    $query = "
        SELECT 
            s.student_number,
            s.first_name AS student_first_name,
            s.last_name AS student_last_name,
            a.first_name AS admission_first_name,
            a.last_name AS admission_last_name,
            a.email AS admission_email
        FROM 
            students s
        INNER JOIN 
            admission_form a 
        ON 
            s.last_name = a.last_name
        WHERE 
            s.student_number = ? AND a.email = ?
    ";
    $stmt = $connection->prepare($query);

    if ($stmt) {
        $stmt->bind_param('ss', $student_number, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['admission_first_name'] . ' ' . $row['admission_last_name'];

            // Generate a random password
            $generated_password = bin2hex(random_bytes(4)); // 8-character password
            $hashed_password = password_hash($generated_password, PASSWORD_DEFAULT);

            // Insert user into users table
            $query = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            $role = 'student'; // Default role
            $created_at = date('Y-m-d H:i:s');
            $stmt->bind_param('sssss', $name, $email, $hashed_password, $role, $created_at);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id; // Get the last inserted user_id
                
                // Update the students table with the generated user_id
                $updateQuery = "
                    UPDATE students 
                    SET user_id = ? 
                    WHERE student_number = ? AND email = ?
                ";
                $updateStmt = $connection->prepare($updateQuery);
                $updateStmt->bind_param('iss', $user_id, $student_number, $email);

                if ($updateStmt->execute()) {
                    // Email notification
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
                        $mail->SMTPAuth = true;
                        $mail->Username = 'sweetmiyagi@gmail.com';  // Your email (sender's email)
                        $mail->Password = 'vbzj pxng toyc xmht';  // Your Gmail app password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('sweetmiyagi@gmail.com', 'SMEC Portal');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Registration Complete';
                        $mail->Body = "
                            <p>Dear $name,</p>
                            <p>Your registration on the portal is successful.</p>
                            <p><strong>Your generated password is: $generated_password</strong></p>
                            <p>We require you to change your password immediately after you login for security purposes.</p>
                            <p>Best regards,</p>
                            <p>Portal Team</p>
                        ";

                        $mail->send();
                        echo "Registration successful. Email sent.";
                    } catch (Exception $e) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    }
                } else {
                    echo "Failed to update student user_id.";
                }
            } else {
                echo "Failed to create user account.";
            }
        } else {
            echo "Student number or email is incorrect, or student is not enrolled.";
        }
    }

    $connection->close();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Account Registration - SMEC</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>


</head>


<body class="h-screen">
    


    <div class="grid grid-cols-1 xl:grid-cols-2 h-full gap-2">

    <div class="flex flex-col  justify-between p-4 space-y-12">

        
        <a class="flex items-center gap-4" href="../../">
       
            <img src="./../../assets/images/defaultLogo.png" alt="" class="w-10 h-10 object-cover">
         
        </a>

        <div class="m-auto flex flex-col items-center w-full lg:w-[580px] ">
            <div class="space-y-3 w-full text-center">
                <h5 class="text-2xl font-bold">Portal Account Registration</h5>
                <p class="text-slate-500">Access the MIS Portal with Your Personalized Account.</p>
            </div>
    
            <form method="POST" class="mt-8 space-y-6 w-full">

          
                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Student Number</label>
                            <div class="relative flex items-center">
                            <input name="student_number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your student number" />
                        
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Email</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" />
                        
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-end mx-3">
                           

                            <div class="text-sm">
                            <a href="jajvascript:void(0);" class="text-blue-600 hover:underline font-semibold">
                                Need Help?
                            </a>
                            </div>
                        </div>

                        <div class="!mt-8">
                            <button type="submit" name="proceed" class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Proceed to Registration
                            </button>
                        </div>
                        <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->

                        <div class="flex items-center justify-center">
                            <div class="g-recaptcha" data-sitekey="6LfIZ5EqAAAAAGeXLXbd-FE6FjKxV-VKz4wfSLM2"></div>
                        </div>
            </form>

            
            <?php if (isset($error)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($warning)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $warning; ?></div>
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


    <div class="w-full h-full hidden xl:grid justify-center items-center bg-blue-200">
                <img src="../../assets/images/studentPortalAnim.gif" alt="" class=" h-1/2 w-full">
    </div>





    </div>





    
</body>
</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

