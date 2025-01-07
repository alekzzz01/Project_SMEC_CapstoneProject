<?php
session_start();

if (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
    // Redirect to OTP page if OTP hasn't been verified yet
    header('Location: otpAuth.php');
    exit();
}

include '../../config/db.php';

$resultEvent = $connection->query("SELECT COUNT(*) AS total_events FROM events");
$row = $resultEvent->fetch_assoc();
$total_events = $row['total_events'];


$sql = "SELECT * FROM events";

$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$events = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

$connection->close();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>


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

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12 h-full">
        

        <div class="space-y-7">
    
        <h4 class="text-3xl font-bold text-blue-500">Hello, Student 👋</h4>

        <div class="p-9 bg-white rounded-md space-y-6">

                <p class="text-xl font-semibold">Campus</p>

                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-100 ">
                    <ul class="flex flex-wrap gap-2 -mb-px">
                        <li class="me-2 flex items-center">
                            <a href="#" class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Events <span class="rounded-full bg-blue-50 px-3 py-2 text-xs"><?php echo $total_events; ?></span></a>
                        
                        </li>
                       
                        <li class="me-2">
                            <a href="#" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Announcements</a>
                        </li>

                        <li class="me-2">
                            <a href="#" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Services</a>
                        </li>
                    
                    </ul>
                </div>

                <div class="events grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <?php
                        if (!empty($events)) {
                            foreach ($events as $row) {
                                echo '<div class="p-6 border border-gray-100 rounded-md flex flex-col justify-between h-[550px]">';
                                    echo '<div class=" space-y-4">';
                                        echo '<img src="../admin/' . $row['banner'] . '" alt="" class="w-full h-56 object-cover rounded-md">';

                                        echo '<p class="text-sm text-gray-400">By <span class="text-black ml-1">' . $row['organizer_name'] . '</span></p>';
                                                            
                                        echo '<p class="text-lg font-bold">' . htmlspecialchars(mb_strimwidth($row['event_name'], 0, 65, "...")) . '</p>';

                                        $eventDateFrom = date("jS M Y", strtotime($row['date_time_from']));
                                        $eventDateTo = date("jS M Y", strtotime($row['date_time_to']));
                                        // echo '<p class="text-gray-400">' . $eventDate . '</p>';

                                        echo '<div class="flex items-center gap-3">
                                                           
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                            </svg>

                                                <div class="flex items-center justify-between w-full">
                                                    <div>
                                                        <p class="text-gray-400 text-sm">From</p>
                                                        <p class> '. $eventDateFrom .'</p>
                                                    </div>

                                                     <div>
                                                        <p class="text-gray-400 text-sm">To</p>
                                                        <p class> '. $eventDateTo .'</p>
                                                    </div>


                                                </div>

                                                
                                        </div>';

                                        echo '<div class="flex items-center gap-3">
                                                           
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                </svg>


                                                <div>
                                                    <p class="text-gray-400 text-sm">Venue</p>
                                                    <p class> '. $row['venue'] .'</p>
                                                </div>
                         
                                        </div>';


                                  
                                
                                    echo '</div>';

                                    echo '<div class="w-full text-end">';
                                        echo '<a href="eventView.php?event_id=' . $row['event_id'] . '" class="font-medium text-blue-600 dark:text-blue-500 hover:underline text-end ">Read more</a>';
                                    echo '</div>';

                                echo '</div>';



                            }

                        }
                             
                        ?>

                        <!-- Sample Card of Event -->
                        <!-- <div class="p-5 shadow-md rounded-md space-y-12">
                                <div class=" space-y-3">
                                    <img src="https://a.storyblok.com/f/178900/1920x1080/fc9956de7a/dandadan-key-art-wide.png/m/1200x0/filters:quality(95)format(webp)" alt="" class="w-full h-56 object-cover">
                                    
                                    <div>
                                        <p class="text-lg font-bold">Event 1</p>
                                        <p>Happenings at the institution</p>
                                    </div>

                                    <p class="text-gray-400">December 03, 2024</p>

                                </div>
                                
                                <div class="w-full text-end">
                                    
                                    <a href="eventView.php" class="font-medium text-blue-600 dark:text-blue-500 hover:underline text-end ">Read more</a>

                                </div>

                        </div> -->

                     
                </div>


        </div>

        </div>

    </div>





    
</body>
</html>