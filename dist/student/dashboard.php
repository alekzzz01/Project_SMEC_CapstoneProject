<?php
session_start();

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
                                echo '<div class="p-5 shadow-md rounded-md space-y-12">';
                                echo '<div class=" space-y-3">';
                                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['banner']) . '" alt="" class="w-full h-56 object-cover">';
                                echo '<div>';
                                echo '<p class="text-lg font-bold">' . $row['event_name'] . '</p>';
                                // echo '<p>' . $row['description'] . '</p>';
                                echo '</div>';
                                $eventDate = date("F d, Y", strtotime($row['date_time_from']));
                                echo '<p class="text-gray-400">' . $eventDate . '</p>';
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