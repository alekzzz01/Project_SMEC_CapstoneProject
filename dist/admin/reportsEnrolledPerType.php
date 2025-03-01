<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">


    




</head>

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">


        <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#fafbfc] h-full">

            <h1 class="text-lg font-medium "></h1>

            <div class="flex items-center justify-between">


                <h1 class="text-lg font-medium ">📊 Student Enrollment by Type</h1>


                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="./">Dashboard</a></li>
                        <li><a>Reports</a></li>
                        <li><a>Student Enrollment by Type</a></li>
                      
                    </ul>
                </div>


            </div>

            <?php include('./tables/enrollmentReportType.php'); ?>




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