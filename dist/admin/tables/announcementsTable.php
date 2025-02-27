<?php

// Include the database connection file
include '../../config/db.php';
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

$sql = "SELECT * FROM notifications WHERE type = 'system'";  // Base query

// Conditions for filtering and search
$conditions = [];
$params = [];
$param_types = "";

// Add search query condition if provided
if ($search_query) {
    $conditions[] = "title LIKE ?";
    $params[] = "%" . $search_query . "%";
    $param_types .= "s";
}

// Append conditions correctly
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

// Correctly add ORDER BY after conditions
$sql .= " ORDER BY created_at DESC";

$stmt = $connection->prepare($sql);

// Bind parameters if there are any
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$announcements = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Close the connection
$connection->close();


// output announcement

// echo "<pre>";
// print_r($announcements);



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


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

<body>

    <div class="flex flex-col lg:flex-row items-center justify-between gap-4 px-5 pt-5">

        <form method="GET">
            <div class="flex items-center gap-4 text-sm">

                <label class="input input-sm input-bordered flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                    </svg>
                    <input type="search" name="search_query" class="grow" placeholder="Search event name" value="<?php echo htmlspecialchars($search_query); ?>" />

                </label>

            

            </div>


        </form>


        <div class="flex items-center justify-end gap-3">

            <button onclick="announce_Modal.showModal()" class="   flex items-center justify-center text-white bg-amber-600 hover:bg-amber-800 focus:ring-4 focus:ring-amber-300 font-medium rounded-md text-sm px-4 py-2 dark:bg-amber-600 dark:hover:bg-amber-700 focus:outline-none dark:focus:ring-amber-800">
                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Announcements
            </button>


        </div>


    </div>



    <!-- Table -->
    <div class="flex flex-col mt-7">

        <div class="overflow-hidden">

            <?php if ($search_query): ?>
                <table id="example" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>

                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">TItle</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Message</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Created At</th>
                        
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        // Check if there are any announcements
                        if (!empty($announcements)) {
                            // Loop through each event
                            foreach ($announcements as $row) {
                                echo '<tr>';

                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">' . htmlspecialchars($row['title']) . '</td>';

                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['message']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['target_role']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars(date('F j, Y, g:i a', strtotime($row['created_at']))) . '</td>';

                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          
                        
                            </td>';



                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">No announcement found.</td></tr>';
                        }
                        ?>
                    </tbody>

                </table>

            <?php else: ?>
                <p class="my-7 text-gray-600 text-center">No data to display. Please apply filters or search.</p>
            <?php endif; ?>


        </div>
    </div>


    <!-- Dialog Modal for each event banner -->
    <?php
    if (!empty($events)) {
        foreach ($events as $row) {
            if (!empty($row['banner'])) {
                echo '<dialog id="imageDialog' . $row['event_id'] . '" class="modal">';
                echo '<div class="modal-box">';
                echo '<img src="' . $row['banner'] . '" alt="Event Banner" class="w-full h-auto" />';
                echo '<div class="modal-action">';
                // Close button that uses form="dialog"
                echo '<form method="dialog">';
                echo '<button class="btn">Close</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</dialog>';
            }
        }
    }
    ?>


    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#example').DataTable({
                dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"ip>',
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        className: 'bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600',
                        title: function() {
                            var selectedSchoolYear = $('#schoolYearFilter').val();
                            if (selectedSchoolYear) {
                                return 'Sta. Martha Educational Inc. - Enrollment reports of ' + selectedSchoolYear;
                            }
                            return 'Enrollment reports'; // Default title
                        },

                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var rows = sheet.getElementsByTagName('row');
                            var lastRowIndex = rows.length; // Get the last row index in the sheet

                            // Create the new row data you want to append after the data
                            var newRow = '<row r="' + (lastRowIndex + 1) + '">';
                            newRow += '<c t="inlineStr" r="A' + (lastRowIndex + 1) + '"><is><t>Generated by: <?php echo $user_name; ?> | School Year: ' + $('#schoolYearFilter').val() + ' | Date: ' + new Date().toLocaleString() + '</t></is></c>';
                            newRow += '<c t="inlineStr" r="B' + (lastRowIndex + 1) + '"><is><t></t></is></c>';
                            newRow += '</row>';

                            // Append the new row to the end of the sheet
                            sheet.getElementsByTagName('sheetData')[0].innerHTML += newRow;
                        },


                        exportOptions: {
                            columns: ':visible' // Export only visible columns
                        },
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
                pageLength: 10,
                language: {
                    paginate: {
                        next: 'Next »',
                        previous: '« Previous'
                    }
                }
            });


        });
    </script>


</body>

</html>