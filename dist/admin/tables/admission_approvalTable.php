<?php
ob_start(); // Start output buffering to prevent header issues
$response = null; // Variable to store the response status
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 
include '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $studentId = $_POST['student_id'];

        // Fetch student details from the admission_form table
        $query = "SELECT * FROM admission_form WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admissionData = $result->fetch_assoc();

        if (!$admissionData) {
            $response = 'error'; // No data found
            echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?status=error';</script>";
            exit;
        }

        // Generate student number
        $currentYear = date("Y");
        $numericGradeLevel = preg_replace('/[^0-9]/', '', $admissionData['year_level']);
        $formattedGradeLevel = str_pad($numericGradeLevel, 2, "0", STR_PAD_LEFT);
        $studentNumberPrefix = $currentYear . '-' . $formattedGradeLevel . '-';

        // Query to find the last sequence for this prefix
        $query = "SELECT MAX(CAST(SUBSTRING_INDEX(student_number, '-', -1) AS UNSIGNED)) AS last_seq 
                  FROM students 
                  WHERE student_number LIKE CONCAT(?, '%')";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $studentNumberPrefix);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $lastSeq = $row['last_seq'] ?? 0;

        // Increment the sequence to generate a new student number
        $newSeq = str_pad($lastSeq + 1, 4, "0", STR_PAD_LEFT);
        $studentNumber = $studentNumberPrefix . $newSeq;

        // Insert student data into the students table
        $defaultUserId = NULL; // Set user_id to NULL temporarily
        $insertQuery = "INSERT INTO students 
                        (user_id, student_number, first_name, middle_initial, last_name, date_of_birth, gender, year_level, 
                         parent_first_name, parent_middle_initial, parent_last_name, region, province, city, barangay, 
                         zip_code, contact_number, email, emergency_first_name, emergency_last_name, relationship) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $connection->prepare($insertQuery);
        $insertStmt->bind_param(
            "issssssssssssssssssss", 
            $defaultUserId, 
            $studentNumber,
            $admissionData['first_name'],
            $admissionData['middle_initial'],
            $admissionData['last_name'],
            $admissionData['birth_date'],
            $admissionData['gender'],
            $admissionData['year_level'],
            $admissionData['parent_first_name'],
            $admissionData['parent_middle_initial'],
            $admissionData['parent_last_name'],
            $admissionData['region'],
            $admissionData['province'],
            $admissionData['city'],
            $admissionData['barangay'],
            $admissionData['zip_code'],
            $admissionData['phone'],
            $admissionData['email'],
            $admissionData['emergency_first_name'],
            $admissionData['emergency_last_name'],
            $admissionData['relationship']
        );

        if ($insertStmt->execute()) {
            // Send approval email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sweetmiyagi@gmail.com';
                $mail->Password = 'vbzj pxng toyc xmht'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');
                $mail->addAddress($admissionData['email'], $admissionData['first_name']);
                $mail->isHTML(true);
                $mail->Subject = 'Admission Approved';
                $mail->Body = "Dear {$admissionData['first_name']},<br>
                               Your admission has been approved. Your student number is <b>{$studentNumber}</b>.<br>
                               Please wait for further instructions regarding enrollment.";

                $mail->send();
                $response = 'success'; // Approval successful
                $action = 'approve';
            } catch (Exception $e) {
                $response = 'error'; // Email sending failed
            }

            // Update the is_confirmed field in the admission_form table
            $updateQuery = "UPDATE admission_form SET is_confirmed = 1 WHERE id = ?";
            $updateStmt = $connection->prepare($updateQuery);
            $updateStmt->bind_param("i", $studentId);
            $updateStmt->execute();
        } else {
            $response = 'error'; // Insert failed
        }
    } elseif (isset($_POST['reject'])) {
        $studentId = $_POST['student_id'];

        // Fetch student details and send rejection email
        $query = "SELECT * FROM admission_form WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admissionData = $result->fetch_assoc();

        if (!$admissionData) {
            $response = 'error'; // No data found
            echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?status=error';</script>";
            exit;
        }

        // Send rejection email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sweetmiyagi@gmail.com';
            $mail->Password = 'vbzj pxng toyc xmht';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('sweetmiyagi@gmail.com', 'Sta. Marta Educational Center');
            $mail->addAddress($admissionData['email'], $admissionData['first_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Admission Rejected';
            $mail->Body = "Dear {$admissionData['first_name']},<br>
                           We regret to inform you that your admission application has been rejected.<br>
                           Thank you for your interest in Sta. Marta Educational Center.";

            $mail->send();
            $response = 'success'; // Rejection successful
            $action = 'reject';
        } catch (Exception $e) {
            $response = 'error'; // Email sending failed
        }

        // Update the is_confirmed field in the admission_form table
        $updateQuery = "UPDATE admission_form SET is_confirmed = -1 WHERE id = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bind_param("i", $studentId);
        $updateStmt->execute();
    }

    // Redirect with JavaScript
    echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?status=$response&action=$action';</script>";
    exit;

}



ob_end_flush(); // End output buffering
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

    
</head>
<body>

  


    <?php
    $query = "SELECT * FROM admission_form WHERE is_confirmed = 0";
    $result = $connection->query($query);

    if ($result->num_rows > 0): ?>


    <div class="flex flex-col">
      
            <div class="overflow-hidden">
                    <table id="example" class="min-w-full divide-y divide-gray-200">
                                <thead class="border border-gray-300  text-sm">
                            <tr>
                                <th>Application ID</th>
                                <th>Applicant Name</th>
                                <th>Grade Level Applied</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                                <th>Action</th>
                             
                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>
                                        <!-- : If you want to avoid extra spaces when one of the fields is missing, you could use this approach: -->
                                        <?php 
                                            echo htmlspecialchars(trim($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name']));
                                        ?>
                                    </td>
                                    <td><?php echo $row['year_level']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>

                            

                                    <td>
                                    
                                        <form method="POST" >
                                            <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                        
                                            <button type='submit' name='approve' class='text-green-500 text-sm hover:underline'>[Approve]</button>
                                            <button type='submit' name='reject' class='text-red-500 text-sm hover:underline'>[Reject]</button>
                                            <button type='button' class='text-amber-500 text-sm hover:underline'>[Send Email]</button>
                                        
                        
                                        
                                        </form>

                                    
                                    
                                    </td>
                 
                                

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    </div>
            
        </div>





        <?php else: ?>
            <p>No unapproved admission requests found.</p>
        <?php endif; ?>
    


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
    $('#example').DataTable({
        searching: true, // Enables the search box
        paging: true,    // Enables pagination
        ordering: true,  // Enables column sorting
        info: true       // Displays table information (e.g., "Showing 1 to 10 of 50 entries")
    });
});
</script>

<script>
    // Initialize Notyf
const notyf = new Notyf({
    duration: 3000, // Duration of the notification (3 seconds)
    position: {
        x: 'center', // Align notifications to the center
        y: 'top'    // Show notifications at the top
    }
});

// Check for `status` and `action` query parameters in the URL
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');
const action = urlParams.get('action');

// Display notifications based on `status` and `action`
if (status === 'success') {
    if (action === 'approve') {
        notyf.success('Admission Approved!');
    } else if (action === 'reject') {
        notyf.success('Admission Rejected!');
    }
} else if (status === 'error') {
    notyf.error('An error occurred. Please try again.');
}
</script>

<script>
    // Remove the 'status' query parameter after the page loads
    document.addEventListener('DOMContentLoaded', function () {
        const url = new URL(window.location.href);
        if (url.searchParams.has('status')) {
            url.searchParams.delete('status'); // Remove the 'status' parameter
            window.history.replaceState({}, document.title, url.pathname); // Update the URL without reloading
        }
    });
</script>