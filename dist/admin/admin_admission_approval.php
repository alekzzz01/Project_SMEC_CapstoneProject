<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';  // Ensure PHPMailer is correctly included
include '../../config/db.php';  // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the student number from the form
    $student_number = $_POST['student_number'];  

    // Update the student's status to "confirmed"
    $query = "UPDATE admission_form SET is_confirmed = 1 WHERE student_number = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $student_number);

    if ($stmt->execute()) {
        // Fetch the student's email and first name after updating the status
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
                $mail->setFrom('jeromedala2002@gmail.com', 'Sta. Marta Educational Center');  // Sender's email
                $mail->addAddress($email, $first_name);  // Recipient's email (the student's email from DB)

                // Email subject and body
                $mail->isHTML(true);
                $mail->Subject = 'Admission Confirmed';
                $mail->Body    = "
                    <p>Dear $first_name,</p>
                    <p>Your admission request has been successfully approved. Welcome to our institution!</p>
                    <p>We are excited to have you join us. Please feel free to reach out if you have any questions.</p>
                    <p>Best regards,</p>
                    <p>Sta. Marta Educational Center</p>
                ";

                // Send the email
                $mail->send();
                $_SESSION['success'] = "Admission request approved and confirmation email sent!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = "Student's email not found.";
        }

        // Redirect back to the page to refresh the table
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "Failed to approve the request.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Admission Requests</title>
    
</head>
<body>
    <h1>Unapproved Admission Requests</h1>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }

    // Fetch all unapproved admission requests
    $query = "SELECT student_number, first_name, email FROM admission_form WHERE is_confirmed = 0";
    $result = $connection->query($query);
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>Student Number</th>
                <th>First Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['student_number']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <!-- Approve Button -->
                        <form action="admin_admission_approval.php" method="POST">
                            <input type="hidden" name="student_number" value="<?php echo $row['student_number']; ?>">
                            <button type="submit" name="approve">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>