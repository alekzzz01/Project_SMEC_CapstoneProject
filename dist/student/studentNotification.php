<?php
session_start();
include '../../config/db.php';



// getting user id
$user_id = $_SESSION['user_id'];


// mark as read button update status column at user notifications to read
if (isset($_POST['markRead'])) {
    $sql = "UPDATE user_notifications SET status = 'read' WHERE user_id = '$user_id'";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        header('Location: studentNotification.php');
    }
}




// Getting user notifications
$sql = "SELECT notifications.*, user_notifications.status 
        FROM user_notifications 
        JOIN notifications ON user_notifications.notification_id = notifications.notification_id
        WHERE user_notifications.user_id = '$user_id'
        ORDER BY user_notifications.status ASC";
        ;

$result = mysqli_query($connection, $sql);

if ($result) {
    $notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    mysqli_free_result($result);
} else {
    $notifications = [];
    $count = 0;
}

mysqli_close($connection);

// echo "<pre>";
// print_r($notifications);





?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>

</head>

<body class="bg-[#f7f7f7] min-h-screen">


    <?php include './components/navbar.php' ?>


    <div class="max-w-7xl mx-auto py-14 px-4">


        <div class="flex items-center justify-between w-full ">

            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li>Notifications</li>
                </ul>
            </div>




        </div>

        <div class="bg-white rounded-md mt-7 shadow-md">

            <div class="p-7 border-b border-gray-100">
                <p class="text-xl font-bold">Notifications </p>
            </div>

            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-100 px-7 pt-2">
                <ul class="flex flex-wrap gap-2 -mb-px">
                    <li class="me-2 flex items-center">
                        <a href="#" class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">All</a>

                    </li>


                </ul>
            </div>

            <div>


                <?php foreach ($notifications as $notification) :
                    $bgColor = ($notification['status'] == 'sent') ? 'bg-gray-50' : 'bg-white'; // Unread = gray background
                ?>
                    <a class="p-7 flex items-start gap-4 border-b border-gray-100 hover:bg-gray-100 transition-colors group <?php echo $bgColor; ?>">

                        <div class="h-2 w-2 rounded-full <?php echo ($notification['status'] == 0) ? 'bg-blue-500' : 'bg-gray-300'; ?> mt-2"></div>
                        <div>
                            <p class="mb-1">
                                <span class="font-bold"><?php echo $notification['title']; ?></span> - <?php echo $notification['message']; ?>
                            </p>
                            <p class="text-sm text-gray-400">
                                <?php
                                $date = date_create($notification['created_at']);
                                echo date_format($date, "M d, Y");
                                ?>
                                •
                                <span class="capitalize"><?php echo $notification['status']?></span>
                            </p>
                        </div>

                    </a>
                <?php endforeach; ?>



                <!-- <a class="p-7 flex items-start gap-4 border-b border-gray-100 bg-gray-50 transition-colors group">

                    <div class="h-2 w-2 rounded-full bg-blue-500 mt-2"></div>
                    <div>
                        <p class="mb-1"><span class="font-bold">New Notification</span> from - School Administrator</p>
                        <p class="text-sm text-gray-400">Now</p>
                    </div>

                </a> -->




                <form action="" class="px-7 py-2 flex items-start gap-4 border-b border-gray-100" method="POST">
                    <button type="submit" name="markRead" class="btn btn-ghost">Mark all as read</button>

                </form>




            </div>



        </div>


</body>

</html>