<?php
include '../../config/db.php';

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>

<div class="space-y-3.5 mt-7">
    <form method="GET" action="">
        <div class="flex justify-between items-center p-6 bg-white rounded-md border border-gray-200">
            <!-- School Year Filter -->
            <div class="flex items-center gap-2">
                <label for="schoolYearFilter" class="text-gray-700 mr-2">Filter by School Year:</label>
                <select name="school_year" id="schoolYearFilter" class="select select-bordered select-sm">
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
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-2">
                <label for="statusFilter" class="text-gray-700 mr-2">Filter by Status:</label>
                <select name="status" id="statusFilter" class="select select-bordered select-sm">
                    <option value="">All</option>
                    <option value="Enrolled" <?php echo ($status_filter === 'Enrolled') ? 'selected' : ''; ?>>Enrolled</option>
                    <option value="Pending" <?php echo ($status_filter === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Rejected" <?php echo ($status_filter === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                    <option value="Correction" <?php echo ($status_filter === 'Correction') ? 'selected' : ''; ?>>Needs Correction</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="flex flex-col p-6 bg-white rounded-md border border-gray-200">
        <table id="example" class="min-w-full divide-y divide-gray-200">
            <thead class="border border-gray-300 text-sm">
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
