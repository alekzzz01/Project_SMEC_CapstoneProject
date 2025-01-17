<?php

include '../../config/db.php';

// Query to fetch enrollment details for open school year
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

        sy.school_year AS school_year_label,

        s.student_number AS student_number_label,
        CONCAT(s.first_name, ' ', s.last_name) AS student_name,

        sec.section_name AS section_name_labels
     
    FROM 
        student_enrollment e


    LEFT JOIN     -- Join the school_year tabel to get school year details from the school_year_id column of enrollment_table
        school_year sy 
    ON 
        e.school_year_id = sy.school_year_id

    LEFT JOIN  -- Join the students table to get student details from the student_id column of enrollment_table
        students s 
    ON 
        e.student_id = s.student_id

 
    LEFT JOIN -- Join the sections table to get section details from the section_id column of enrollment_table
        sections sec
    ON
        e.section = sec.section_id

    WHERE 
        sy.status = 'Open' -- Filter to show only open school years

    ORDER BY 
        e.date_enrolled DESC
";

$result = $connection->query($sql);


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

<div class="flex justify-end items-center">
      
            <!-- School Year Filter -->
            <div class="flex items-center gap-2">
                <label for="schoolYearFilter" class="text-gray-700 mr-2 ">Filter by Status:</label>
                <select id="schoolYearFilter" class="select select-bordered select-sm">
                    <option value="">All</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Correction">Needs Correction</option>
                </select>

            </div>

    </div>



    <div class="flex flex-col mt-7">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="divide-y divide-gray-200">
                <div class="overflow-hidden">
                            <table id="example" class="min-w-full divide-y divide-gray-200">
                                <thead class="border border-gray-300  text-sm">
                                    <tr>
                                         <th>Enrollment ID No.</th>
                                        <th>Student Type</th>
                                        <th>Student Number</th>
                                        <th>Student Name</th>
                                        <th>Grade Level</th>
                                        <th>Section</th>                      
                                        <th>Track</th>
                                        <th>Type</th>                 
                                        <th>Status</th>
                                        <th>Date Enrolled</th>
                                        <th>Actions</th>
                                        <th></th>
                                    
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 border border-gray-300 ">
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['enrollment_id']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['student_type']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['student_number_label']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['student_name']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['grade_level']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['section_name_labels']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['track']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['type']}</td>                                                                                                             
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>{$row['enrollment_status']}</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>" . date('M. d, Y', strtotime($row['date_enrolled'])) . "</td>
                                                            <td class='px-6 py-4 whitespace-nowrap  text-gray-800'>
                                                                <form>
                                                                    <button class='text-green-500'>[Approve]</button>
                                                                    <button class='text-red-500'>[Reject]</button>
                                                                       <button class='text-amber-500'>[Verify Payment]</button>
                                                                </form>
                                                            </td>
                                                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>
                                                                    <a class='text-green-500'>[View Details]</a>                                                                
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

    // Event listener for the filter dropdown
    $('#schoolYearFilter').on('change', function () {
        var filterValue = $(this).val(); // Get the selected value

        // If value is empty (All), remove the filter
        if (filterValue) {
            table.column(8).search('^' + filterValue + '$', true, false).draw();
        } else {
            table.column(8).search('').draw();
        }
    });
});

</script>
