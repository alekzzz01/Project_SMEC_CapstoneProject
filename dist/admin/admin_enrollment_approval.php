<?php 


include '../../config/db.php';

// Query to output the open school year

$sql = "SELECT * FROM school_year WHERE status = 'Open'";
$result = $connection->query($sql);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $school_year = $row['school_year'];
    }
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Approval</title>


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


<body class="flex min-h-screen">


<?php include('./components/sidebar.php'); ?>
    

    <div class="flex flex-col w-full">

        
        <?php include('./components/navbar.php'); ?>

        <div class="p-7 bg-gray-50 h-full">

                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-bold mb-1">Enrollment Approval</h1>
                        <p class="text-gray-400">Approve enrollment for the open school year.</p>
                    </div>

                    <p class="text-gray-400">Current School Year: <span class="text-black"><?php echo $school_year ?></span></p>
                </div>

                <div class=" p-6 bg-white rounded-md mt-7">
                <div>
                    <?php include('./tables/enrollment_approvalTable.php'); ?>
                </div>

            </div>
        </div>



    </div>





</body>
</html>