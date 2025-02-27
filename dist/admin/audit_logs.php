<?php
session_start();
include '../../config/db.php';

// Query for getting the name of the user who performed the action and the audit logs
$sql = "
        SELECT 
        u.name,
        a.* 
        FROM audit_logs a
        LEFT JOIN users u ON a.user_id = u.user_id
";

// Check if there is a search query
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

// Conditions for filtering and search
$conditions = [];
$params = [];
$param_types = "";

// Add search query condition if provided
if (!empty($search_query)) {
    $conditions[] = "u.name LIKE ?";
    $params[] = "%" . $search_query . "%";
    $param_types .= "s";
}

// Append conditions correctly
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Correctly add ORDER BY after conditions
$sql .= " ORDER BY a.created_at DESC";

$stmt = $connection->prepare($sql);

// Bind parameters if there are any
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results
$auditLogs = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Close the connection
$connection->close();

// Debugging Output (Optional)
// echo '<pre>';
// print_r($auditLogs);
// echo '</pre>';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>




</head>

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">

        <!-- Navbar -->

        <?php include('./components/navbar.php'); ?>

        <!-- Content -->

        <div class="p-6 bg-[#fafbfc] h-full">
            <div class="flex items-center justify-between">


                <h1 class="text-lg font-medium mb-1">Audit Logs</h1>

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a>Audit Logs</a></li>
                    </ul>
                </div>


            </div>

            <div class="rounded-md mt-7 bg-white border border-gray-200">
                <div class="flex items-center justify-between w-full px-5 pt-5">


                    <form method="GET" class="flex items-center justify-end">
                        <div class="flex items-center gap-4 text-sm">

                            <label class="input input-sm input-bordered flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                                    <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                                </svg>
                                <input type="search" name="search_query" class="grow" placeholder="Search user" value="<?php echo htmlspecialchars($search_query); ?>" />

                            </label>
                        </div>
                    </form>





                </div>


                <div class="flex flex-col mt-7">


                    <div class="overflow-hidden">
                        <?php if (!$search_query): ?>
                            <table class="min-w-full divide-y divide-gray-200 ">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3 px-4 pe-0">
                                            <div class="flex items-center h-5">
                                                <input id="hs-table-pagination-checkbox-all" type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                                                <label for="hs-table-pagination-checkbox-all" class="sr-only">Checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Date</th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Action</th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">User</th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Resource Type</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <?php
                                    // Check if there are any events
                                    if (!empty($auditLogs)) {
                                        // Loop through each event
                                        foreach ($auditLogs as $row) {
                                            echo '<tr>';
                                            echo '<td class="py-3 ps-4">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                                                    </div>
                                                    </td>';
                                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">' . htmlspecialchars(date('F j, Y, g:i a', strtotime($row['created_at'])))  . '</td>';
                                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['action']) . '</td>';
                                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['name']) . '</td>';
                                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['resource_type']) . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">No Audit Logs.</td></tr>';
                                    }
                                    ?>
                                </tbody>


                            <?php else: ?>
                                <p class="my-7 text-gray-600 text-center">No data to display.</p>
                            <?php endif; ?>

                            </table>

                    </div>




                </div>


            </div>






        </div>


    </div>



</body>

</html>


<script>
    $(document).ready(function() {
        $('#toggleSidebar').on('click', function() {
            $('#sidebar').toggleClass('-translate-x-full');
        });

        $('#closeSidebar').on('click', function() {
            $('#sidebar').addClass('-translate-x-full');
        });



    });
</script>