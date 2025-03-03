<?php
session_start();
include '../../config/db.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable('../../config');
$dotenv->load();

// check if user_id is provided in the GET request
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // SQL query to fetch user details
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
    } else {
        echo json_encode(["error" => "User not found"]);
        // Redirect to the users page
        header("Location: users.php");
        exit();
    }
} else {
    echo json_encode(["error" => "User ID not provided"]);
    // Redirect to the users page
    header("Location: users.php");
    exit();
}

// update user account details
if (isset($_POST['updateUser'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $created_at = $_POST['created_at'];


    $sql = "UPDATE users SET name = ?, email = ?, role = ?, created_at = ? WHERE user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $role, $created_at, $user_id);
    if ($stmt->execute()) {
        $response = 'success'; // Approval successful
        $action = 'approve';
    } else {
        $response = 'error'; // Error 
        $action = 'errorStatus';
    }

    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?user_id=$user_id&status=$response&action=$action';</script>";
    exit;
}

// update password of user

if (isset($_POST['passwordChange'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Retrieve user details
        $sql = "SELECT name, birth_date, email FROM users WHERE user_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();

        if (!$userData) {
            echo json_encode(["error" => "User data not found"]);
            exit();
        }

        $name = $userData['name'];
        $email = $userData['email'];
        $birthdate = $userData['birth_date']; // YYYY-MM-DD format

        // Generate password for encrypted PDF
        $nameInitials = strtoupper(substr($name, 0, 2));
        $pdfPassword = $nameInitials . str_replace('-', '', $birthdate);

        // Update password and lockout status in the database
        $sql = "UPDATE users SET password = ?, lockout = 0 WHERE user_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $user_id);

        if ($stmt->execute()) {
            try {
                // Initialize TCPDF
                $pdf = new TCPDF();
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Sta. Marta Educational Center');
                $pdf->SetTitle('Password Update Notification');
                $pdf->SetSubject('Password Update');
                $pdf->SetMargins(15, 15, 15);
                $pdf->AddPage();

                // Content
                $html = "
                    <h2>New Credentials for $email</h2>
                    <p>Dear $name,</p>
                    <p>Your password has been updated successfully.</p>
                    <p><strong>New Password:</strong> $password</p>
                    <p>Please change your password immediately. Thank you!</p>
                ";
                $pdf->writeHTML($html, true, false, true, false, '');

                // Encrypt PDF
                $pdf->SetProtection(['modify', 'copy', 'print'], $pdfPassword, null, 0);

                // Save PDF
                $pdf_file = __DIR__ . "/../../temp/password_$user_id.pdf"; // Adjust the path based on your directory structure

                // Ensure the directory exists
                if (!is_dir(dirname($pdf_file))) {
                    mkdir(dirname($pdf_file), 0777, true);
                }
                $pdf->Output($pdf_file, 'F');

                // Send Email
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sweetmiyagi@gmail.com';
                $mail->Password = 'euuy nadj ibmd acau';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Password Updated';
                $mail->Body = "
                    <h2>Password Update Notification</h2>
                    <p>Dear $name,</p>
                    <p>Your password has been updated successfully.</p>
                    <p>For security reasons, your new password is inside this PDF.</p>
                    <p><strong>To open the PDF, use this password:</strong> First two letters of your name + your birthdate (YYYYMMDD)</p>
                    <p>Sample:</p>
                    <p>Name: JUAN DELACRUZ</p>
                    <p>Birthdate: July 01, 1994</p>
                    <p>Password Format:<strong>JU19940701</p>
                    <p>Please change your password immediately. Thank you!</p> 
                ";
                $mail->addAttachment($pdf_file, "Updated_Password.pdf");
                $mail->send();

                // Delete temp file
                unlink($pdf_file);

                // email sent

                $response = 'success'; // Approval successful
                $action = 'emailSent';
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?user_id=$user_id&status=$response&action=$action';</script>";
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">


    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>


    <html data-theme="light">

    </html>

</head>

<body class="flex min-h-screen">


    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

        <?php include('./components/navbar.php'); ?>

        <div class="p-5 bg-[#fafbfc] h-full">


            <div class="flex items-center justify-between">


                <h1 class="text-lg font-medium mb-1">Edit Users</h1>

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="users.php">Users</a></li>
                        <li>Edit</li>
                    </ul>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 my-7">



                <form action="" method="POST" class="bg-white rounded-md border border-gray-200 p-5 space-y-4" enctype="multipart/form-data">

                    <p class="font-medium">Account Details</p>

                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Name</label>
                        <input name="name" type="text" value="<?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>" required class="w-full bg-gray-50 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Event Name" />
                    </div>

                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Email</label>
                        <div class="relative flex items-center">
                            <input name="email" type="text" value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>" required class="w-full bg-gray-50 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter organizer name" value="<?php echo $event['organizer_name'] ?>" />

                        </div>
                    </div>


                    <div class="col-span-2">
                        <label class="text-gray-800 text-sm mb-2 block">Role</label>
                        <div class="relative flex items-center">
                            <select name="role" id="role" required class="w-full text-gray-800 bg-gray-50 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" onchange="toggleStudentNumberField()">
                                <option value="" disabled>Select role</option>
                                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="teacher" <?php echo $user['role'] == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                                <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Created at</label>
                        <div class="relative flex items-center">
                            <input name="created_at" type="text" value="<?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($user['created_at'])), ENT_QUOTES, 'UTF-8'); ?>" required class="w-full bg-gray-50 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter created at date" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Status</label>
                        <div class="relative flex items-center">
                            <input name="lockout_status" type="text" readonly required value="<?php echo $user['lockout'] == 1 ? 'Lockout' : 'Active'; ?>" class="w-full bg-gray-50 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter lockout status" />
                        </div>
                    </div>


                    <div class="modal-action col-span-2">
                        <a href="users.php" class="btn bg-gray-500 hover:bg-gray-700 text-white border border-gray-500 hover:border-gray-700">Cancel</a>
                        <button type="submit" name="updateUser" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Update User</button>
                    </div>


                </form>


                <form action="" method="POST" class="bg-white rounded-md border border-gray-200 p-5 space-y-4" x-data="app()">

                    <p class="font-medium">Account Recovery</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-base-content/70 text-sm mb-2 block">New Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" x-model="password" @input="checkStrength" class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="text-base-content/70 text-sm mb-2 block">Confirm Password</label>
                            <div class="relative flex items-center">
                                <input id="confirmPassword" name="confirmPassword" type="password" class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Confirm password" />
                                <button type="button" onclick="togglePassword('confirmPassword', 'toggleConfirmPasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="toggleConfirmPasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-span-2 space-y-2">

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

                        </div>



                        <div class="modal-action col-span-2">

                            <!-- button for generate password -->
                            <button type="button" onclick="generateRandomPassword()" class="btn bg-gray-500 hover:bg-gray-700 text-white border border-gray-500 hover:border-gray-700">Generate Password</button>
                            <button type="submit" name="passwordChange" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed" :disabled="passwordScore < 4">Update Password</button>
                        </div>

                    </div>


                </form>







            </div>





        </div>





    </div>





</body>

</html>




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

    // Display notifications based on `status` and `action`
    if (status === 'success') {
        if (action === 'approve') {
            notyf.success('User updated successfully!');
        } else if (action === 'update') {
            notyf.success('Password updated successfully!');
        } else if (action === 'emailSent') {
            notyf.success('Password updated successfully! Email sent.');
        }
    } else if (status === 'error') {
        if (action === 'errorStatus') {
            notyf.error('Failed to update user!');
        } else if (action === 'passwordDbError') {
            notyf.error('Failed to update password!');
        } else if (action === 'passwordDoNotMatch') {
            notyf.error('Passwords do not match!');
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const url = new URL(window.location.href);

        // Store user ID before modifying the URL
        const userId = url.searchParams.get('user_id');

        // Remove 'status' and 'action' parameters
        url.searchParams.delete('status');
        url.searchParams.delete('action');

        // Preserve the user_id if it exists
        if (userId) {
            url.searchParams.set('user_id', userId);
        }

        // Update the URL without reloading
        window.history.replaceState({}, document.title, url.pathname + '?' + url.searchParams.toString());
    });
</script>

<script>
    function generateRandomPassword() {
        const length = 16; // Password length
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }

        // Update password fields
        document.querySelector('[name="password"]').value = password;
        document.querySelector('[name="confirmPassword"]').value = password;

        // Trigger Alpine.js update
        let alpineComponent = document.querySelector('[x-data="app()"]');
        if (alpineComponent) {
            alpineComponent.__x.$data.password = password; // Update Alpine.js password state
            alpineComponent.__x.$data.checkStrength(); // Recalculate strength
        }
    }
</script>


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
                if (this.password.length >= 16) {
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