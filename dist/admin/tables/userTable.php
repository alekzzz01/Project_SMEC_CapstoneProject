<?php

include '../../config/db.php';

// $userResult = $connection->query("SELECT COUNT(*) AS total_users FROM users");
// $userRow = $userResult->fetch_assoc();
// $userCount = $userRow['total_users'];

$role_type_filter = isset($_GET['role_type']) ? $_GET['role_type'] : '';
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

$sql = "SELECT * FROM users";

// Conditions for filtering and search
$conditions = [];
$params = [];
$param_types = "";

// Add role type condition if selected
if ($role_type_filter) {
  $conditions[] = "role = ?";
  $params[] = $role_type_filter;
  $param_types .= "s";
}

// Add search query condition if provided
if ($search_query) {
  $conditions[] = "email LIKE ?";
  $params[] = "%" . $search_query . "%";
  $param_types .= "s";
}

// Combine conditions into the SQL query
if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $connection->prepare($sql);

// Bind parameters if there are any
if (!empty($params)) {
  $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();

$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$connection->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

  <script src="https://cdn.tailwindcss.com"></script>


  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- DataTables CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS CDN -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script defer>
    $(document).ready(function() {
      $('#myTable').DataTable({
        "lengthMenu": [10, 25, 50, 75, 100],
        "pageLength": 10,
        "pagingType": "full_numbers",

        responsive: true
      });
    });
  </script>
</head>

<body>

  <div class="flex items-center justify-between w-full px-5 pt-5">


    <form method="GET" class="flex items-center justify-end">
      <div class="flex items-center gap-4 text-sm">

        <label class="input input-sm input-bordered flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
            <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
          </svg>
          <input type="search" name="search_query" class="grow" placeholder="Search user email" value="<?php echo htmlspecialchars($search_query); ?>" />

        </label>

        <!-- Role Type Filter -->
        <select name="role_type" class="select select-bordered select-sm w-full" onchange="this.form.submit()">
          <option value="" disabled selected>Select Role</option> <!-- Disabled and selected by default -->
          <option value="Admin" <?php if ($role_type_filter == 'Admin') echo 'selected'; ?>>Admin</option>
          <option value="Teacher" <?php if ($role_type_filter == 'Teacher') echo 'selected'; ?>>Teacher</option>
          <option value="Student" <?php if ($role_type_filter == 'Student') echo 'selected'; ?>>Student</option>
        </select>


      </div>
    </form>


    <button onclick="add_user.showModal()" type="button" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
      <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
      </svg>
      Add User
    </button>


  </div>


  <div class="flex flex-col mt-7">


    <div class="overflow-hidden">
      <?php if ($search_query || $role_type_filter): ?>
        <table class="min-w-full divide-y divide-gray-200 ">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="py-3 px-4 pe-0">
                <div class="flex items-center h-5">
                  <input id="hs-table-pagination-checkbox-all" type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                  <label for="hs-table-pagination-checkbox-all" class="sr-only">Checkbox</label>
                </div>
              </th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">User ID</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Email</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Role</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-700 uppercase">Created At</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            <?php
            // Check if there are any events
            if (!empty($users)) {
              // Loop through each event
              foreach ($users as $row) {
                echo '<tr>';
                echo '<td class="py-3 ps-4">
                                  <div class="flex items-center h-5">
                                      <input type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                                  </div>
                                </td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">' . htmlspecialchars($row['user_id']) . '</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['email']) . '</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['role']) . '</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['created_at']) . '</td>';

                //   echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                //   <a href="campusEditEvent.php?event_id=' . $row['event_id'] . '" 
                //     type="button" 
                //     class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                //     Edit
                //   </a>
                //   <button onclick="deleteEvent(' . $row['event_id'] . ')" 
                //           type="button" 
                //           class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-none focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none">
                //           Delete
                //   </button>
                // </td>';


                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">No user found.</td></tr>';
            }
            ?>
          </tbody>


        <?php else: ?>
          <p class="my-7 text-gray-600 text-center">No data to display. Please apply filters or search.</p>
        <?php endif; ?>

        </table>

    </div>

    <!-- <div class="py-1 px-4">
              <nav class="flex items-center space-x-1" aria-label="Pagination">
                <button type="button" class="p-2.5 min-w-[40px] inline-flex justify-center items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" aria-label="Previous">
                  <span aria-hidden="true">«</span>
                  <span class="sr-only">Previous</span>
                </button>
                <button type="button" class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none" aria-current="page">1</button>
                <button type="button" class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none">2</button>
                <button type="button" class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none">3</button>
                <button type="button" class="p-2.5 min-w-[40px] inline-flex justify-center items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" aria-label="Next">
                  <span class="sr-only">Next</span>
                  <span aria-hidden="true">»</span>
                </button>
              </nav>
            </div> -->



  </div>




</body>

</html>