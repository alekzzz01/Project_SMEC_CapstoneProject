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
    <title>Analytics</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>



</head>

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">


        <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">

            <h1 class="text-lg font-medium ">📊 Reports</h1>

            <div role="tablist" class="tabs tabs-lifted mt-3.5">
                <input type="radio" name="my_tabs_2" role="tab" class="tab"  checked="checked" aria-label="🎓 Enrollment" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    <?php include('./tables/enrollmentReportTable.php'); ?>
                </div>


                <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="👨‍🏫 Student" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">Tab content 3</div>

                <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="✍️ Teacher" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">Tab content 3</div>

            </div>


            <h1 class="text-lg font-medium mt-7">📈 Analytics</h1>

            <div class="rounded-xl p-4 bg-white border border-gray-200 mt-3.5">
                <?php include('./charts/totalEnrollmentLineChart.php'); ?>
            </div>



        </div>



    </div>




</body>

</html>