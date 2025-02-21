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

    <div class="py-12  px-4 lg:px-12 border-b border-gray-100"> 
            
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-100 ">
                    <ul class="flex flex-wrap gap-2 -mb-px">
                      
                        <li class="me-2">
                            <a href="./" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300">Home</a>
                        </li>

                        <li class="me-2 flex items-center">
                            <a href="portals.php" class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Portals</a>
                        </li>
                       

                        <li class="me-2">
                            <a href="forms.php" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300">Forms</a>
                        </li>
                    
                    </ul>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <img src="../assets/images/portal/enrollment.png" alt="" class="w-full h-[240px] object-cover">
                        <p class=" text-xl mb-6 mt-3 font-semibold">Online Enrollment</p>
    
                         <a href="../schoolServices/enrollment/" class=" text-sm bg-blue-700 hover:bg-blue-800 transition-colors py-2 px-4  text-white rounded-md inline-flex items-center gap-2">
                                <span>View Portal</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                        </a>
    
                    </div>

                    <div>
                        <img src="../assets/images/portal/admission.png" alt="" class="w-full h-[240px]">
                        <p class=" text-xl mb-6 mt-3 font-semibold">Online Admission</p>
    

                         <a href="../schoolServices/admission/" class=" text-sm bg-blue-700 hover:bg-blue-800 transition-colors py-2 px-4  text-white rounded-md inline-flex items-center gap-2">

                  
                                <span>View Portal</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                        </a>
    
                    </div>

                    <div>
                        <img src="" alt="" class="w-full h-[240px]">
                        <p class=" text-xl mb-6 mt-3 font-semibold">Portal Account Registration</p>
    

                         <a href="../schoolServices/registrationPortal" class=" text-sm bg-blue-500 hover:bg-blue-700 transition-colors py-2 px-4  text-white rounded-md inline-flex items-center gap-2">

                  
                                <span>View Portal</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                        </a>
    
                    </div>








                </div>

            
              
               
            </div>

    </div>


    
    <?php include '../footer.php' ?>
      
  

    
</body>
</html>


