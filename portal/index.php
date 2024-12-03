<?php
session_start();

include '../config/db.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Portals</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
     
 
     <script src="https://cdn.tailwindcss.com"></script>
 
     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
 
     <script src="https://cdn.tailwindcss.com"></script>
 
     <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
 
     <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
 
     <html data-theme="light"></html>


    <!--JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

     
     
</head>
<body>


    <?php include 'navbar.php' ?>

  
    <div class="py-36 px-4 lg:px-12  bg-blue-800 flex items-center justify-center"> 
            
            <h1 class="text-4xl lg:text-6xl font-semibold text-white mb-4 shadow-sm">School Portals</h1>

    </div>

    <div class="py-12  px-4 lg:px-12"> 
            
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-100 ">
                    <ul class="flex flex-wrap gap-2 -mb-px">
                        <li class="me-2 flex items-center">
                            <a href="./" class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Home</a>
                        
                        </li>
                       
                        <li class="me-2">
                            <a href="portals.php" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Portals</a>
                        </li>

                        <li class="me-2">
                            <a href="forms.php" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Forms</a>
                        </li>
                    
                    </ul>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-lg">Access portals through this links</p>
                        <li>Online Enrollment</li>
                        <li>Admission</li>
                        <a href="">Other Online Portals</a>
                    </div>

                    <div>
                        <p class="text-lg">Access Downloadable forms</p>
                        <li>Payment Slip</li>
                        <li>Medical Form</li>
                        <li>Receipt</li>
                        <a href="">Other Forms</a>
                    </div>
              
        
                </div>
              
               
            </div>

    </div>


    
    <?php include '../footer.php' ?>
      
  

    
</body>
</html>


