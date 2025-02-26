<?php

include '../../config/db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT notifications.*
        FROM user_notifications 
        JOIN notifications ON user_notifications.notification_id = notifications.notification_id
        WHERE user_notifications.user_id = '$user_id'";

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



<nav class="bg-white md:border-b lg:border-b border-gray-200 shadow md:shadow-none md:bg-transparent p-2 flex justify-between items-center">

    <div class="flex items-center gap-6">
        <button id="toggleSidebar" class="md:hidden focus:outline-none btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
            </svg>
        </button>


    </div>


    <div class="flex items-center gap-4">

        <div class="dropdown dropdown-end  h-full">
            <div tabindex="0" role="button" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>

            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-5 w-[500px] p-2 shadow">


                <!-- looping through the notifications -->
                <?php foreach (array_slice($notifications, 0, 4) as $notification): ?>
                    <li class="p-2">
                        <a class="space-x-4">
                            <div class="p-4 bg-gray-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                </svg>
                            </div>
                            <div class="space-y-2 text-wrap">
                                <p class="font-bold text-lg"><?php echo $notification['title']; ?></p>
                                <p><?php echo $notification['message']; ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">
                                    <?php
                                    $date = date_create($notification['created_at']);
                                    echo date_format($date, "M d, Y");
                                    ?>
                                </p>

                            </div>
                        </a>
                    </li>

                <?php endforeach; ?>

                <!-- if no notifications is available -->

                <?php if ($count == 0): ?>
                    <li class="p-2">
                        <a >
                            There are no notifications available
                        </a>
                    </li>

                <?php endif; ?>




                <div class=" text-center p-2">
                    <a href="#" class="text-blue-500">View All</a>
                </div>


            </ul>
        </div>


        <div class="dropdown dropdown-end h-full">


            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar online">
                <div class="w-10 rounded-full">
                    <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                </div>
            </div>

            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-5 w-52 p-2 shadow">
                <li>
                    <a href="profile.php" class="justify-between">
                        Profile
                        <span class="badge">New</span>
                    </a>
                </li>
                <li><a>Settings</a></li>
                <li><a href="./../../auth/logout.php">Logout</a></li>
            </ul>
        </div>







    </div>



</nav>

<script src="../../assets/js/script.js"></script>