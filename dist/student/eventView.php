<?php

session_start();
include '../../config/db.php';

if (isset($_GET['event_id'])) {

    $event_id = $_GET['event_id'];

    $sql = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = 'Event not found';
        header('Location: ./dashboard.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Event ID not provided';
    header('Location: ./dashboard.php');
    exit;
}


$eventStartDate = date("F d, Y, g:i A", strtotime($event['date_time_from']));
$eventEndDate = date("F d, Y, g:i A", strtotime($event['date_time_to']));


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event View</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   
</head>
<body class="bg-[#f7f7f7] min-h-screen">


    <?php include './components/navbar.php' ?>

    <div class=" max-w-7xl mx-auto py-14 px-4">


                <?php if (isset($_SESSION['message'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10  mb-7"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10  mb-7" ><?= $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>



    <div class="flex items-center justify-between w-full ">
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="dashboard.php">Events</a></li>
            <li><?= htmlspecialchars($event['event_name'])?></li>
        </ul>
        </div>

        

    </div>

    <div class="py-7 px-7 xl:px-20 bg-white rounded-md mt-7">



        <?php
            // Check if there is a banner image
            if ($event['banner']) {
                // If a banner image exists, display it
                echo '<img src="data:image/jpeg;base64,'.base64_encode($event['banner']).'" alt="Event Banner" class="w-full h-56 object-cover rounded-md">';
            } else {
                // If there is no banner, display a message
            echo '<p class="mb-6">No banner available for this event.</p>';
                }
        ?>

        <div class="flex items-center justify-between mt-12">

       

        <p class="text-sm font-medium text-blue-500"><?= htmlspecialchars($eventStartDate) ?></p>


            <div class="flex items-center gap-4">
                <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                </a>

                <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                </svg>
                </a>
            </div>


        </div>

        <div class="space-y-8">
       
            <div class="mt-6">
                <h1 class="font-bold text-4xl"><?= htmlspecialchars($event['event_name'])?></h1>
            </div>

            <div class="border-b border-neutral-100"></div>

            <div class="px-4 flex items-center justify-between">

                <div class="flex items-center gap-4">
                    <img src="https://gkids.com/wp-content/uploads/2024/06/DANDA_Poster_RGB_Digital_EpisodeText-1-702x1024.jpg" alt="" class="w-12 h-12 object-cover rounded-full">

                    <div>
                        <p class="text-lg font-semibold mb-1"><?= htmlspecialchars($event['organizer_name'])?></p>
                        <p class="text-gray-400 font-light"><?= htmlspecialchars($event['organizer_type'])?></p>
                    </div>
                </div>

                <div>
                    <a class="border border-blue-400 px-4 py-2 rounded-full font-bold text-sm text-blue-500 hover:bg-blue-50">Page</a>
                </div>
            </div>

            <div class="border-b border-neutral-100"></div>

            <div class="flex flex-wrap gap-4">
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5  mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <div>
                        <p class="font-bold  mb-1">Date and Time</p>
                        <p class="text-gray-400 font-light"><?= htmlspecialchars($eventStartDate) ?> – <?= htmlspecialchars($eventEndDate) ?></p>
                    </div>
                </div>

                <div class="border-r border-neutral-100"></div>

                <div class="flex items-start gap-4 ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>

                    <div>
                        <p class="font-bold  mb-1">Location</p>
                        <p class="text-gray-400 font-light"><?= htmlspecialchars($event['venue'])?></p>
                    </div>
                </div>


            </div>

        </div>

          

    </div>



    </div>

    
</body>
</html>