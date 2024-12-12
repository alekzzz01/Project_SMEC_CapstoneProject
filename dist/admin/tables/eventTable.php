<?php
include '../../config/db.php';

// Initialize variables from the GET request
$event_type_filter = isset($_GET['event_type']) ? $_GET['event_type'] : '';
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

// Base SQL query
$sql = "SELECT * FROM events WHERE is_archived = 0";  // Add condition to exclude archived events

// Conditions for filtering and search
$conditions = [];
$params = [];
$param_types = "";

// Add event type condition if selected
if ($event_type_filter) {
    $conditions[] = "event_type = ?";
    $params[] = $event_type_filter;
    $param_types .= "s";
}

// Add search query condition if provided
if ($search_query) {
    $conditions[] = "event_name LIKE ?";
    $params[] = "%" . $search_query . "%";
    $param_types .= "s";
}

// Combine conditions into the SQL query
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions); // Use AND to combine conditions
}

$stmt = $connection->prepare($sql);

// Bind parameters if there are any
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch events
$events = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Close the connection
$connection->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <form method="GET" class="flex items-center justify-end">
      <div class="flex items-center gap-4 text-sm">

        <label class="input input-sm input-bordered flex items-center gap-2">
            <input type="text" name="search_query" class="grow" placeholder="Search event name" value="<?php echo htmlspecialchars($search_query); ?>" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
            </svg>
        </label>

        <!-- Event Type Filter -->
        <select name="event_type" class="select select-bordered select-sm w-full" onchange="this.form.submit()">
            <option value="" <?php if ($event_type_filter == '') echo 'selected'; ?>>All Events</option> <!-- Option to clear the filter -->
            <option value="Public" <?php if ($event_type_filter == 'Public') echo 'selected'; ?>>Public</option>
            <option value="Private" <?php if ($event_type_filter == 'Private') echo 'selected'; ?>>Private</option>
        </select>



      </div>
    </form>


    <!-- Table -->
    <div class="flex flex-col mt-7">
      <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
          <div class="border rounded-lg divide-y divide-gray-200">
            <div class="overflow-hidden">

            <?php if ($search_query || $event_type_filter): ?>
              <table class="min-w-full divide-y divide-gray-200">
                <thead>
                  <tr>
                    <th scope="col" class="py-3 px-4 pe-0">
                      <div class="flex items-center h-5">
                        <input id="hs-table-pagination-checkbox-all" type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                        <label for="hs-table-pagination-checkbox-all" class="sr-only">Checkbox</label>
                      </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Event Name</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Event Venue</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Type</th>
                    <!-- <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Description</th> -->
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Created At</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Banner Image</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php
                // Check if there are any events
                if (!empty($events)) {
                    // Loop through each event
                    foreach ($events as $row) {
                        echo '<tr>';
                        echo '<td class="py-3 ps-4">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                                </div>
                              </td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">' . htmlspecialchars($row['event_name']) . '</td>';
                       
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['venue']) . '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['event_type']) . '</td>';
                        // echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['description']) . '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">' . htmlspecialchars($row['created_at']) . '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">';
                        if (!empty($row['banner'])) {
                            echo '<button class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none" onclick="document.getElementById(\'imageDialog' . $row['event_id'] . '\').showModal()">View Image</button>';
                        } else {
                            echo 'No banner';
                        }
                        echo '</td>';
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="campusEditEvent.php?event_id=' . $row['event_id'] . '" 
                           type="button" 
                           class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                           Edit
                        </a>
                    
                        <button onclick="archiveEvent(' . $row['event_id'] . ')" 
                                type="button" 
                                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-600 hover:text-gray-800 focus:outline-none focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none">
                                Archive
                        </button>
                      </td>';
                
                

                        echo '</tr>';

                     
                    }
                } else {
                    echo '<tr><td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">No events found.</td></tr>';
                }
                ?>
              </tbody>

              </table>

              <?php else: ?>
                    <p class="my-7 text-gray-600 text-center">No data to display. Please apply filters or search.</p>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Dialog Modal for each event babanner -->
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


</body>
</html>


