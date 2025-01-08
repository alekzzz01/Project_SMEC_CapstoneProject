
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
            
            <h1 class="text-4xl lg:text-6xl font-semibold text-white mb-4 shadow-sm">Admissions</h1>

    </div>

    <div class="py-12  px-4 lg:px-12 border-b border-gray-100"> 
            
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-100 ">
                    <ul class="flex flex-wrap gap-2 -mb-px">

                        <li class="me-2 flex items-center">
                            <a href="./" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300" aria-current="page">Home</a>
                        </li>
                      
                        <li class="me-2">
                            <a href="elementaryRequirements.php" class="inline-block p-3 text-blue-600  border-b-2 active border-blue-600 rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300">Elementary</a>
                        </li>

                        <li class="me-2">
                            <a href="forms.php" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300">Junior High School</a>
                        </li>

                        <li class="me-2">
                            <a href="forms.php" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300">Senior High School</a>
                        </li>

                        <li class="me-2">
                            <a href="forms.php" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300">Senior High School Programs</a>
                        </li>
                    
                    </ul>
                </div>

                <div class="space-y-3">
                        

                    
                        <div class="join join-vertical w-full border">
                        <div class="collapse collapse-arrow join-item border-base-300 border-b pb-6">
                            <input type="radio" name="my-accordion-4" checked="checked" />
                            <div class="collapse-title text-xl font-medium">Qualifications</div>
                            <div class="collapse-content text-sm font-light  text-gray-500">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce cursus dapibus tellus, eget suscipit ante egestas eleifend. </p>
                            </div>
                        </div>
                        <div class="collapse collapse-arrow join-item border-base-300 border-b py-6">
                            <input type="radio" name="my-accordion-4" />
                            <div class="collapse-title text-xl font-medium">Requirements</div>
                            <div class="collapse-content text-sm font-light text-gray-500">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce cursus dapibus tellus, eget suscipit ante egestas eleifend. </p>
                            </div>
                        </div>
                        <div class="collapse collapse-arrow join-item border-base-300 border-b py-6">
                            <input type="radio" name="my-accordion-4" />
                            <div class="collapse-title text-xl font-medium">Procedures</div>
                            <div class="collapse-content text-sm font-light text-gray-500">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce cursus dapibus tellus, eget suscipit ante egestas eleifend. </p>
                            </div>
                        </div>
                        </div>


                </div>

            
              
               
            </div>

    </div>


    

  

    
</body>
</html>



