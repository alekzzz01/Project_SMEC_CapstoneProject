<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';  // Ensure PHPMailer is correctly included
include '../../config/db.php';  // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
    // Get student ID (admission_form ID) from the form submission
    $studentId = $_POST['student_id'];

    // Fetch student details from the admission_form table
    $query = "SELECT * FROM admission_form WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $admissionData = $result->fetch_assoc();

    if (!$admissionData) {
        echo "No admission record found.";
        exit;
    }

    // Generate the sequential student number
    // Generate the sequential student number
$currentYear = date("Y");

// Extract numeric grade level and ensure it's two-digit
$numericGradeLevel = preg_replace('/[^0-9]/', '', $admissionData['year_level']); // Extract numeric part
$formattedGradeLevel = str_pad($numericGradeLevel, 2, "0", STR_PAD_LEFT); // Convert to two digits

// Generate the student number prefix
$studentNumberPrefix = $currentYear . '-' . $formattedGradeLevel . '-';

// Query to find the last sequence
$query = "SELECT MAX(CAST(SUBSTRING(student_number, 10) AS UNSIGNED)) AS last_seq FROM students WHERE SUBSTRING(student_number, 1, 9) = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $studentNumberPrefix);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$lastSeq = $row['last_seq'] ?? 0;
$newSeq = str_pad($lastSeq + 1, 4, "0", STR_PAD_LEFT);

// Construct the new student number
$studentNumber = $studentNumberPrefix . $newSeq;

    // Insert the data into the students table directly from admission_form
$insertQuery = "
INSERT INTO students (student_number, first_name, last_name, date_of_birth, gender, contact_number)
VALUES (?, ?, ?, ?, ?, ?)
";
$insertStmt = $connection->prepare($insertQuery);
$insertStmt->bind_param(
"ssssss",
$studentNumber,
$admissionData['first_name'],
$admissionData['last_name'],
$admissionData['birth_date'],
$admissionData['gender'],
$admissionData['phone']
);

if ($insertStmt->execute()) {
echo "Student data inserted successfully.";

// Update the is_confirmed field in the admission_form table
$updateQuery = "UPDATE admission_form SET is_confirmed = 1 WHERE id = ?";
$updateStmt = $connection->prepare($updateQuery);
$updateStmt->bind_param("i", $studentId);

if ($updateStmt->execute()) {
    echo "Admission form updated successfully.";
} else {
    echo "Error updating admission form.";
}

        // Send the email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'sweetmiyagi@gmail.com';  // Your email (sender's email)
            $mail->Password = 'niui ynqm ojtp bwva';  // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
            $mail->Port = 587;  // SMTP Port

            // Sender and recipient details
            $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');  // Sender's email
            $mail->addAddress($admissionData['email'], $admissionData['first_name']);  // Recipient's email
            $mail->isHTML(true);
            $mail->Subject = 'Your Admission Has Been Approved';
            $mail->Body = "Dear {$admissionData['first_name']},<br><br>
                           Congratulations! Your admission has been approved. Your student number is: <b>{$studentNumber}</b>.<br><br>
                           Regards,<br>School Administration";

            $mail->send();
            echo "Student number generated and email sent successfully!";
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error inserting data into the students table.";
    }

    // Do not close the connection here as it's reused later
    $stmt->close();
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
<!-- Fetch Unapproved Admission Requests -->
<h1>Unapproved Admission Requests</h1>
<?php
$query = "SELECT id, first_name, last_name, birth_date, gender, phone, email FROM admission_form WHERE is_confirmed = 0";
$result = $connection->query($query);

if ($result->num_rows > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Date</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['birth_date']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="approve">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No unapproved admission requests found.</p>
<?php endif; ?>
</body>
</html>