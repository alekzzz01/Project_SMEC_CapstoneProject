<?php


include '../../config/db.php';


$sql = "
    SELECT 
        e.enrollment_id,
        e.student_id,
        e.subjectEnrolled,
        e.school_year_id AS enrolled_school_year,
        e.type,
        e.grade_level,
        e.strand,
        e.section,
        e.date_enrolled,
        e.status AS enrollment_status,

        sy.school_year AS school_year_label,

        s.student_number AS student_number_label,
        CONCAT(s.first_name, ' ', s.last_name) AS student_name,

        sec.section_name AS section_name_labels
     
     
    FROM 
        student_enrollment e

    LEFT JOIN 
        school_year sy
    ON 
        e.school_year_id = sy.school_year_id

    LEFT JOIN 
        students s 
    ON 
        e.student_id = s.student_id

    LEFT JOIN 
        sections sec
    ON
        e.section = sec.section_id
        
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
    <title>TailwindCSS Datatable with Export Buttons</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <!-- Buttons Extensions -->
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
</head>
<body class="bg-gray-100 py-10">

    <div class="container mx-auto">

    <div class="mb-4 flex justify-between items-center">
            <!-- School Year Filter -->
            <div>
                <label for="schoolYearFilter" class="text-gray-700 mr-2">Filter by School Year:</label>
                    <select id="schoolYearFilter" class="border border-gray-300 rounded py-1 px-2">
                        <option value="">All</option>
                            <?php
                                // Fetch distinct school years for the filter dropdown
                            $schoolYearQuery = "SELECT DISTINCT school_year FROM school_year ORDER BY school_year ASC";
                            $schoolYearResult = $connection->query($schoolYearQuery);
                            if ($schoolYearResult->num_rows > 0) {
                                while ($row = $schoolYearResult->fetch_assoc()) {
                                    echo "<option value='{$row['school_year']}'>{$row['school_year']}</option>";
                                }
                            }
                        ?>
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
                            <th class="py-3 px-4 text-left">Enrollment ID</th>
                            <th class="py-3 px-4 text-left">Student Number</th>
                            <th class="py-3 px-4 text-left">Student Name</th>
                            <th class="py-3 px-4 text-left">School year</th>
                            <th class="py-3 px-4 text-left">Type</th>
                            <th class="py-3 px-4 text-left">Strand</th>
                            <th class="py-3 px-4 text-left">Grade Level</th>
                            <th class="py-3 px-4 text-left">Section</th>
                            <th class="py-3 px-4 text-left">Date Enrolled</th>
                            <th class="py-3 px-4 text-left">Enrollment Status</th>
                    
                        
                        </tr>
                    </thead>
                        <tbody class="divide-y divide-gray-200 border border-gray-300 ">
                            <?php
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td  class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['enrollment_id']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['student_number_label']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['student_name']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['school_year_label']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['type']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['strand']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['grade_level']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['section_name_labels']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['date_enrolled']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap text-sm  text-gray-800'>{$row['enrollment_status']}</td>
                                    
                                        </tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "No records found.";
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

          
      
    </div>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('#example').DataTable({
            dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"ip>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600',
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export to PDF',
                    className: 'bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600',
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600',
                }
            ],
            responsive: true,
            pageLength: 5,
            language: {
                paginate: {
                    next: 'Next »',
                    previous: '« Previous'
                }
            }
        });

        // Add event listener for the school year filter dropdown
        $('#schoolYearFilter').on('change', function () {
            var selectedSchoolYear = $(this).val(); // Get selected value
            if (selectedSchoolYear) {
                // Filter table based on school year (column index 3 for school year)
                table.column(3).search('^' + selectedSchoolYear + '$', true, false).draw();
            } else {
                // Show all rows if "All" is selected
                table.column(3).search('').draw();
            }
        });
    });
    </script>

</body>
</html>
