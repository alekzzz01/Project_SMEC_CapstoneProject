<?php
ob_start(); // Start output buffering
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 
include '../../config/db.php';

// Function to send email
function sendEmail($email, $name, $status) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sweetmiyagi@gmail.com'; // Sender's email
        $mail->Password = 'vbzj pxng toyc xmht';  // Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient details
        $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Enrollment Status Update';
        $mail->Body = "Dear $name,<br><br>Your enrollment status has been updated to: <strong>$status</strong>.<br><br>Thank you.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
    }
}

// Handle Approve Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])) {
    if (isset($_POST['enrollment_id'])) {
        $enrollmentId = $_POST['enrollment_id'];

        // Fetch student's email and name
        $query = "SELECT s.email, CONCAT(s.first_name, ' ', s.last_name) AS name FROM students s
                  INNER JOIN student_enrollment e ON s.student_id = e.student_id
                  WHERE e.enrollment_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $enrollmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        // Update the status to 'enrolled'
        $sql = "UPDATE student_enrollment SET status = 'enrolled' WHERE enrollment_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $enrollmentId);

        if ($stmt->execute()) {
            $successMessage = "Enrollment approved successfully!";
            sendEmail($student['email'], $student['name'], 'Enrolled');
        } else {
            $errorMessage = "Error: Unable to approve enrollment.";
        }

        $stmt->close();
    }
}

// Handle Reject Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject'])) {
    if (isset($_POST['enrollment_id'])) {
        $enrollmentId = $_POST['enrollment_id'];

        // Fetch student's email and name
        $query = "SELECT s.email, CONCAT(s.first_name, ' ', s.last_name) AS name FROM students s
                  INNER JOIN student_enrollment e ON s.student_id = e.student_id
                  WHERE e.enrollment_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $enrollmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        // Update the status to 'rejected'
        $sql = "UPDATE student_enrollment SET status = 'rejected' WHERE enrollment_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $enrollmentId);

        if ($stmt->execute()) {
            $successMessage = "Enrollment rejected successfully!";
            sendEmail($student['email'], $student['name'], 'Rejected');
        } else {
            $errorMessage = "Error: Unable to reject enrollment.";
        }

        $stmt->close();
    }
}

// Query to fetch only pending enrollments
$sql = "
    SELECT 
        e.enrollment_id,
        e.subjectEnrolled,
        e.type,
        e.grade_level,
        e.student_type,
        e.track,
        e.section,
        e.date_enrolled,
        e.status AS enrollment_status,
        s.student_id,
        sy.school_year AS school_year_label,
        s.student_number AS student_number_label,
        CONCAT(s.first_name, ' ', s.last_name) AS student_name,
        sec.section_name AS section_name_labels
    FROM 
        student_enrollment e
    LEFT JOIN school_year sy ON e.school_year_id = sy.school_year_id
    LEFT JOIN students s ON e.student_id = s.student_id
    LEFT JOIN sections sec ON e.section = sec.section_id
    WHERE 
        sy.status = 'Open' AND e.status = 'Pending'
    ORDER BY 
        e.date_enrolled DESC
";

$result = $connection->query($sql);
ob_end_flush(); // Flush the buffered output
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- DataTables CSS (Hover Styling) -->
    <link href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    
</head>
<body>

<div class="space-y-3.5 mt-7">

    <div class="p-6 bg-white rounded-md border border-gray-200">
    
                <div class="overflow-hidden">
                            <table id="example" class="min-w-full divide-y divide-gray-200">
                                <thead class=" text-sm">
                                    <tr>
                                         <th>Enrollment ID No.</th>
                                        <th>Student Type</th>
                                        <th>Student Number</th>
                                        <th>Student Name</th>
                                        <th>Grade Level</th>
                                        <th>Type</th>                 
                                        <th>Status</th>
                                        <th>Date Enrolled</th>
                                        <th>Details</th>
                                        <th>Actions</th>
                                  
                                      
                                    
                                    </tr>
                                </thead>

                                <tbody class="">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['enrollment_id']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['student_type']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['student_number_label']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['student_name']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['grade_level']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['type']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . htmlspecialchars($row['enrollment_status']) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>" . date('M. d, Y', strtotime($row['date_enrolled'])) . "</td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm hover:underline text-gray-800'>
                        <a class='text-green-500' href='view_student.php?student_id=" . htmlspecialchars($row['student_id']) . "'>[View Details]</a>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-gray-800'>
                        <form method='POST'>
                            <input type='hidden' name='enrollment_id' value='" . htmlspecialchars($row['enrollment_id']) . "'>
                            <button type='submit' name='approve' class='text-green-500 text-sm hover:underline'>[Approve]</button>
                            <button type='submit' name='reject' class='text-red-500 text-sm hover:underline'>[Reject]</button>
                            <button type='button' class='text-amber-500 text-sm hover:underline'>[Verify Payment]</button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</table>";
    }
    // Close the connection
    $connection->close();
    ?>
</tbody>
                            
                            </table>

                    </div>
                
    </div>

</div>




        <dialog id="send_email" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box p-0">

                <h3 class="font-medium bg-amber-300 p-4">Email Applicant</h3>

                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>

                <form action="" class="p-4" method="POST">

                        <div class="space-y-5">
                        
                            <label  class="block font-regular text-gray-400">To: <span class=" text-black"> email of student </span></label>
                            <label class="block font-regular text-gray-400">From: <span class=" text-black"> SMEC_2025@gmail.com </span></label>

                            <div class="divider"></div>

                            <label class="input input-bordered flex items-center gap-2">
                        
                            <input type="text" class="grow" placeholder="Subject" />
                            </label>

                            <textarea class="textarea textarea-bordered w-full" placeholder="Body"></textarea>

                            
                            
                        </div>

                    
                    
                        <div class="modal-action col-span-2">
                        
                        <button type="submit" name="createUser" class="py-1.5 px-3 rounded-md text-sm transition-colors bg-green-500 hover:bg-green-700 text-white border border-green-500 hover:border-green-700">Send Email</button>
                        <button type="submit" name="createUser" class="py-1.5 px-3 rounded-md text-sm transition-colors bg-red-500 hover:bg-red-700 text-white border border-red-500 hover:border-red-700">Draft</button>

                        </div>
        

                </form>

            </div>
           
          
        </dialog>

</body>
</html>

<script>
$(document).ready(function () {
    var table = $('#example').DataTable({
        searching: true, // Enables the search box
        paging: true,    // Enables pagination
        ordering: true,  // Enables column sorting
        info: true       // Displays table information (e.g., "Showing 1 to 10 of 50 entries")
    });




});

</script>
