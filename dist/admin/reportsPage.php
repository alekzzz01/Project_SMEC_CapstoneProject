<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Reports</title>

      
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   


</head>
<body class="flex lg:h-screen">

    <?php include('./components/sidebar.php'); ?>
    

    <div class="flex flex-col w-full shadow-xl h-full">

        
        <?php include('./components/navbar.php'); ?>

        <div class="p-7 bg-gray-50 h-full">
                <h1 class="text-lg font-bold">Enrollment Reports</h1>

                <div class=" p-6 bg-white rounded-md mt-7">
                <div>
                    <?php include('./tables/enrollmentReportTable.php'); ?>
                </div>

            </div>
        </div>



    </div>




</body>
</html>