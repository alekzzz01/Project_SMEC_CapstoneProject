<?php
session_start();
include '../../config/db.php';

// Fetch school years
$schoolYears = [];
$query = "SELECT school_year_id, school_year, status FROM school_year ORDER BY school_year ASC";
$result = $connection->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schoolYears[] = $row;
    }
}



if (isset($_POST['approve'])) {
    $school_year_id = $_POST['school_year_id'];

    // Start a transaction
    $connection->begin_transaction();

    try {
        // Fetch school year
        $sql = "SELECT school_year FROM school_year WHERE school_year_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $school_year_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $school_year = $row['school_year'];

        // Close any previously opened school year
        $sql_close = "UPDATE school_year SET status = 'Close' WHERE status = 'Open'";
        $connection->query($sql_close);

        // Insert notification message
        $sql1 = "INSERT INTO notifications (title, message, type, target_role) 
                 VALUES ('School Year Opening', 'The new school year $school_year has officially started! Welcome back!', 'event', 'all')";
        $connection->query($sql1);
        $notification_id = $connection->insert_id;

        // Send notification to all users
        $sql2 = "INSERT INTO user_notifications (user_id, notification_id, status, sent_at) 
                 SELECT user_id, ?, 'sent', NOW() FROM users";
        $stmt = $connection->prepare($sql2);
        $stmt->bind_param("i", $notification_id);
        $stmt->execute();

        // Open the selected school year
        $sql_update = "UPDATE school_year SET status = 'Open' WHERE school_year_id = ?";
        $stmt = $connection->prepare($sql_update);
        $stmt->bind_param("i", $school_year_id);
        $stmt->execute();

        // Commit the transaction
        $connection->commit();

        // Redirect to the class term page
        header("Location: class_term.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $connection->rollback();
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['reject'])) {
    $school_year_id = $_POST['school_year_id'];
    $new_status = 'Close';
    $update_query = "UPDATE school_year SET status = '$new_status' WHERE school_year_id = '$school_year_id'";
    $connection->query($update_query);
    header("Location: class_term.php");
    exit();
}




// Handle adding new term
if (isset($_POST['add_term'])) {
    $new_school_year = $_POST['new_school_year'];

    // Fetch the latest school_year_id
    $latest_id_query = "SELECT MAX(school_year_id) AS max_id FROM school_year";
    $latest_id_result = $connection->query($latest_id_query);
    $latest_id_row = $latest_id_result->fetch_assoc();
    $new_school_year_id = $latest_id_row['max_id'] + 1;

    // Insert the new school year with status 'Close'
    $insert_query = "INSERT INTO school_year (school_year_id, school_year, status) VALUES ('$new_school_year_id', '$new_school_year', 'Close')";
    $connection->query($insert_query);
    header("Location: class_term.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Term</title>

    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">


    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>


    <html data-theme="light">

    </html>





</head>

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

        <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">


            <div class="border border-gray-300 rounded bg-white">

                <h1 class="font-semibold p-5 bg-blue-50 rounded-t text-blue-600">Add Term</h1>

                <form action="class_term.php" method="POST" class="p-5 space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div>
                            <label for="new_school_year" class="text-gray-800 text-sm font-medium mb-2 block">Input Year</label>
                            <div class="relative flex items-center">
                                <input id="new_school_year" name="new_school_year" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="e.g. 2024-2025" />
                            </div>

                        </div>

                    </div>

                    <div class=" flex items-center justify-center">
                        <button type="submit" name="add_term" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit</button>
                    </div>




                </form>


            </div>


            <div class="border border-gray-300  bg-white mt-3.5  overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Year ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Year</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($schoolYears as $year): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"><?= htmlspecialchars($year['school_year_id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?= htmlspecialchars($year['school_year']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">

                                    <p class="<?= $year['status'] === 'Open' ? 'px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800"' : 'px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800' ?>"><?= htmlspecialchars($year['status']) ?></p>


                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                    <form method="POST">
                                        <input type="hidden" name="school_year_id" value="<?= htmlspecialchars($year['school_year_id']) ?>">
                                        <button type="submit" name="approve" class="text-green-600 text-sm hover:underline hover:text-green-900">Open</button>
                                        <button type="submit" name="reject" class="ml-2 text-red-500 text-sm hover:underline hover:text-red-900">Close</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>


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