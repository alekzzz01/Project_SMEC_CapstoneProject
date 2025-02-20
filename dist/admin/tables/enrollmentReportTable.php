<?php
include '../../config/db.php';


$user_id = $_SESSION['user_id'];
// Prepare and execute the query to fetch the user's name
$sql = "SELECT name FROM users WHERE user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();

// Bind the result
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();





// Initialize filters with default values
$school_year_filter = isset($_GET['school_year']) ? trim($_GET['school_year']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

// Base SQL query
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
    WHERE sy.status = 'Open'
";

// Add filters to the query
$conditions = [];
if (!empty($school_year_filter)) {
    $conditions[] = "sy.school_year = '" . $connection->real_escape_string($school_year_filter) . "'";
}
if (!empty($status_filter)) {
    $conditions[] = "e.status = '" . $connection->real_escape_string($status_filter) . "'";
}

if (!empty($conditions)) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}

$sql .= " ORDER BY e.date_enrolled DESC";

// Execute the query
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!--Regular Datatables CSS-->
	<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
	<!--Responsive Extension Datatables CSS-->
	<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <!-- Buttons -->
    <link href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.js"></script>

    <style>
		/*Overrides for Tailwind CSS */

		/*Form fields*/
		.dataTables_wrapper select,
		.dataTables_wrapper .dataTables_filter input {
			color: #4a5568;
			/*text-gray-700*/
			padding-left: 1rem;
			/*pl-4*/
			padding-right: 1rem;
			/*pl-4*/
			padding-top: .5rem;
			/*pl-2*/
			padding-bottom: .5rem;
			/*pl-2*/
			line-height: 1.25;
			/*leading-tight*/
			border-width: 2px;
			/*border-2*/
			border-radius: .25rem;
			border-color: #edf2f7;
			/*border-gray-200*/
			background-color: #edf2f7;
			/*bg-gray-200*/
		}

		/*Row Hover*/
		table.dataTable.hover tbody tr:hover,
		table.dataTable.display tbody tr:hover {
			background-color: #ebf4ff;
			/*bg-indigo-100*/
		}

		/*Pagination Buttons*/
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			font-weight: 700;
			/*font-bold*/
			border-radius: .25rem;
			/*rounded*/
			border: 1px solid transparent;
			/*border border-transparent*/
		}

		/*Pagination Buttons - Current selected */
		.dataTables_wrapper .dataTables_paginate .paginate_button.current {
			color: #fff !important;
			/*text-white*/
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
			/*shadow*/
			font-weight: 700;
			/*font-bold*/
			border-radius: .25rem;
			/*rounded*/
			background: #667eea !important;
			/*bg-indigo-500*/
			border: 1px solid transparent;
			/*border border-transparent*/
		}

		/*Pagination Buttons - Hover */
		.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
			color: #fff !important;
			/*text-white*/
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
			/*shadow*/
			font-weight: 700;
			/*font-bold*/
			border-radius: .25rem;
			/*rounded*/
			background: #667eea !important;
			/*bg-indigo-500*/
			border: 1px solid transparent;
			/*border border-transparent*/
		}

		/*Add padding to bottom border */
		table.dataTable.no-footer {
			border-bottom: 1px solid #e2e8f0;
			/*border-b-1 border-gray-300*/
			margin-top: 0.75em;
			margin-bottom: 0.75em;
		}

		/*Change colour of responsive icon*/
		table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before,
		table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
			background-color: #667eea !important;
			/*bg-indigo-500*/
		}
	</style>







</head>

<body>

    <div>


        <form method="GET" action="" class=" px-5 pt-5">
            <div class="flex items-center gap-4">
                <!-- School Year Filter -->
                <select name="school_year" id="schoolYearFilter" class="select select-bordered select-sm">
                    <option value="" selected disabled>Choose School Year</option>
                    <option value="">All</option>
                    <?php
                    $schoolYearQuery = "SELECT DISTINCT school_year FROM school_year ORDER BY school_year ASC";
                    $schoolYearResult = $connection->query($schoolYearQuery);
                    if ($schoolYearResult->num_rows > 0) {
                        while ($row = $schoolYearResult->fetch_assoc()) {
                            $selected = ($school_year_filter === $row['school_year']) ? 'selected' : '';
                            echo "<option value='{$row['school_year']}' $selected>{$row['school_year']}</option>";
                        }
                    }
                    ?>
                </select>

                <!-- Status Filter -->
                <select name="status" id="statusFilter" class="select select-bordered select-sm">
                    <option value="" selected disabled>Choose Status</option>
                    <option value="">All</option>
                    <option value="Enrolled" <?php echo ($status_filter === 'Enrolled') ? 'selected' : ''; ?>>Enrolled</option>
                    <option value="Pending" <?php echo ($status_filter === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Rejected" <?php echo ($status_filter === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="Correction" <?php echo ($status_filter === 'Correction') ? 'selected' : ''; ?>>Needs Correction</option>
                </select>

                <button type="submit" class="btn btn-sm btn-neutral">Filter</button>
            </div>

        

        </form>

        <div class="mt-7 px-5">
        <table id="example" class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                <thead class="border border-gray-300 text-sm bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Student Type</th>
                        <th class="py-3 px-4 text-left">Student Number</th>
                        <th class="py-3 px-4 text-left">Student Name</th>
                        <th class="py-3 px-4 text-left">School Year</th>
                        <th class="py-3 px-4 text-left">Type</th>
                        <th class="py-3 px-4 text-left">Strand</th>
                        <th class="py-3 px-4 text-left">Grade Level</th>
                        <th class="py-3 px-4 text-left">Section</th>
                        <th class="py-3 px-4 text-left">Enrollment Status</th>
                        <th class="py-3 px-4 text-left">Date Enrolled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 border border-gray-300">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['enrollment_id']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['student_type']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['student_number_label']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['student_name']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['school_year_label']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['type']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['track']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['grade_level']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['section_name_labels']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>{$row['enrollment_status']}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-800'>" . date('M. d, Y', strtotime($row['date_enrolled'])) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11' class='text-center py-4'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            responsive: true,
            searching: true, // Enables the search box
            paging: true, // Enables pagination
            ordering: true, // Enables column sorting
            info: true, // Displays table information
            layout: {
                bottomStart: {
                    buttons: [

                        // Copy
                        {
                            extend: 'copyHtml5',
                            title: function() {
                                var selectedSchoolYear = $('#schoolYearFilter').val();
                                if (selectedSchoolYear) {
                                    return 'Sta. Marta Educational Inc. | Enrollment report of ' + selectedSchoolYear;
                                }
                                return 'Sta. Marta Educational Inc. | Enrollment report';
                            },
                            messageBottom: function() {
                                var schoolYear = $('#schoolYearFilter').val();
                                var currentDate = new Date().toLocaleString();
                                var userName = '<?php echo $user_name; ?>';

                                return 'Generated by: ' + userName + ' | School Year: ' + schoolYear + ' | Date: ' + currentDate;
                            }

                        },

                        // Excel
                        {
                            extend: 'excelHtml5',
                            title: function() {
                                var selectedSchoolYear = $('#schoolYearFilter').val();
                                if (selectedSchoolYear) {
                                    return 'Sta. Marta Educational Inc. | Enrollment report of ' + selectedSchoolYear;
                                }
                                return 'Sta. Marta Educational Inc. | Enrollment report';
                            },
                            messageBottom: function() {
                                var schoolYear = $('#schoolYearFilter').val();
                                var currentDate = new Date().toLocaleString();
                                var userName = '<?php echo $user_name; ?>';

                                return 'Generated by: ' + userName + ' | School Year: ' + schoolYear + ' | Date: ' + currentDate;
                            }

                        },

                        // PDF
                        {
                            extend: 'pdf',
                            title: function() {
                                var selectedSchoolYear = $('#schoolYearFilter').val();
                                if (selectedSchoolYear) {
                                    return 'Sta. Marta Educational Inc. | Enrollment report of ' + selectedSchoolYear;
                                }
                                return 'Sta. Marta Educational Inc. | Enrollment report';
                            },
                            messageBottom: function() {
                                var schoolYear = $('#schoolYearFilter').val();
                                var currentDate = new Date().toLocaleString();
                                var userName = '<?php echo $user_name; ?>';

                                return 'Generated by: ' + userName + ' | School Year: ' + schoolYear + ' | Date: ' + currentDate;
                            }
                        },

                        // Print
                        {
                            extend: 'print',
                            title: function() {
                                var selectedSchoolYear = $('#schoolYearFilter').val();
                                if (selectedSchoolYear) {
                                    return 'Sta. Marta Educational Inc. | Enrollment report of ' + selectedSchoolYear;
                                }
                                return 'Sta. Marta Educational Inc. | Enrollment report';
                            },
                            messageBottom: function() {
                                var schoolYear = $('#schoolYearFilter').val();
                                var currentDate = new Date().toLocaleString();
                                var userName = '<?php echo $user_name; ?>';

                                return 'Generated by: ' + userName + ' | School Year: ' + schoolYear + ' | Date: ' + currentDate;
                            }
                        }
                    ]
                }
            }


        });
    });
</script>