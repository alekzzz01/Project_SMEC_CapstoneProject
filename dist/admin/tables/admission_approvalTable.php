<?php

// fix the resubmission issue

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 
include '../../config/db.php';

ob_start(); // Start output buffering to prevent header issues

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
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
    $currentYear = date("Y");
    $numericGradeLevel = preg_replace('/[^0-9]/', '', $admissionData['year_level']);
    $formattedGradeLevel = str_pad($numericGradeLevel, 2, "0", STR_PAD_LEFT);
    $studentNumberPrefix = $currentYear . '-' . $formattedGradeLevel . '-';

    // Query to find the last sequence
    $query = "SELECT MAX(CAST(SUBSTRING(student_number, 10) AS UNSIGNED)) AS last_seq 
              FROM students 
              WHERE SUBSTRING(student_number, 1, 9) = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $studentNumberPrefix);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $lastSeq = $row['last_seq'] ?? 0;
    $newSeq = str_pad($lastSeq + 1, 4, "0", STR_PAD_LEFT);
    $studentNumber = $studentNumberPrefix . $newSeq;

    // Insert data into the students table
    $insertQuery = "INSERT INTO students (student_number, first_name, last_name, date_of_birth, gender, contact_number) 
                    VALUES (?, ?, ?, ?, ?, ?)";
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
        // Update the is_confirmed field in the admission_form table
        $updateQuery = "UPDATE admission_form SET is_confirmed = 1 WHERE id = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bind_param("i", $studentId);
        $updateStmt->execute();

        // Send an email with PHPMailer
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
            $mail->addAddress($admissionData['email'], $admissionData['first_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Your Admission Has Been Approved';
            $mail->Body = "Dear {$admissionData['first_name']},<br><br>
                           Congratulations! Your admission has been approved. Your student number is: <b>{$studentNumber}</b>.<br><br>
                           Regards,<br>School Administration";

            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        echo "<div class='rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7'>
        Student data inserted successfully, and email sent!
         </div>";
    } else {
        echo "<div class='rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7'>
        Error: " . $connection->error . "
        </div>";
    }
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

    
</head>
<body>

  


    <?php
    $query = "SELECT * FROM admission_form WHERE is_confirmed = 0";
    $result = $connection->query($query);

    if ($result->num_rows > 0): ?>


    <div class="flex flex-col mt-7">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="divide-y divide-gray-200">
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
                    <th></th>
                  
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
                             
                                <div class="dropdown  dropdown-left dropdown-end">
                                    
                                        <div tabindex="0" role="button" class="btn btn-circle btn-ghost btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>

                                        </div>
                                    
                                    <div
                                            tabindex="1"
                                            class="menu dropdown-content bg-base-100 z-[1] rounded-md mt-4 w-52 p-2 shadow">
                                            <li>  <button type="submit" name="approve" class="text-sm font-semibold">Approve</button></li>
                                            <li>  <button type="submit" name="reject" class="text-sm font-semibold">Reject</button></li>
                                          
    
                                    </div>
                                </div>

                               
            
                              
                            </form>

                        
                        
                        </td>
                        <td>
                            <div class="tooltip tooltip-left" data-tip="Send Email to Applicant">
 
                            <button onclick="send_email.showModal()" class="btn btn-circle btn-ghost btn-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </button>

                        
                            </div>
                        </td>
                     

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        </div>
            </div>
            </div>
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
