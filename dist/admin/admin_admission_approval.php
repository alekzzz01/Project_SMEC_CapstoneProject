<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';  // Ensure PHPMailer is correctly included

include '../../config/db.php';  // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = $_POST['student_number'];  // Get the student number from the form

    // Update the student's status to "confirmed"
    $query = "UPDATE admission_form SET is_confirmed = 1 WHERE student_number = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $student_number);

    if ($stmt->execute()) {
        // Fetch the student's email and first name
        $query = "SELECT email, first_name FROM admission_form WHERE student_number = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $student_number);
        $stmt->execute();
        $stmt->bind_result($email, $first_name);
        $stmt->fetch();

        // Send the email notification if an email is found
        if (!empty($email)) {
            $mail = new PHPMailer(true);

            try {
                // Server settings for SMTP (Gmail)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'jeromedala2002@gmail.com';  // Your email (sender's email)
                $mail->Password = 'jbef jrqn newa bhqc';  // Your Gmail app password (not the student's email)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
                $mail->Port = 587;  // SMTP Port

                // Sender and recipient details
                $mail->setFrom('jeromedala2002@gmail.com', 'Your School Name');  // Sender's email
                $mail->addAddress($email, $first_name);  // Recipient's email (the student's email from DB)

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Admission Approved - Sta. Marta Educational Center';
                $mail->Body = "
                    <p>Dear $first_name,</p>
                    <p>Congratulations! Your admission has been approved. Here is your student number:</p>
                    <h3>$student_number</h3>
                    <p>You can now log in to your account to proceed with enrollment.</p>
                    <p>Best regards,</p>
                    <p>Sta. Marta Educational Center</p>
                ";

                // Send the email
                $mail->send();
                $message = "Admission approved, and email sent successfully.";
            } catch (Exception $e) {
                $message = "Admission approved, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "Email address not found for this student.";
        }
    } else {
        $message = "Error approving admission: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Admission Approval</title>
</head>
<body>
    <h1>Admin Admission Approval</h1>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="admin_admission_approval.php">
        <label for="student_number">Student Number:</label>
        <input type="text" id="student_number" name="student_number" required>
        <button type="submit">Approve Admission</button>
    </form>
</body>
</html>