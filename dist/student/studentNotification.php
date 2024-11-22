<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   
</head>
<body class="bg-gray-100 h-screen">


    <?php include './layouts/navbar.php' ?>

    <div class="container mx-auto py-14 px-4 lg:px-12">


    <div class="flex items-center justify-between w-full ">
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li>Notifications</li>
        </ul>
        </div>

      
 

    </div>

    <div class="bg-white rounded-md mt-7 shadow-md">

            <div class="p-7 border-b border-gray-100">
                <p class="text-xl font-bold">Notifications </p>
            </div>

            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-100 px-7 pt-2">
                    <ul class="flex flex-wrap gap-2 -mb-px">
                        <li class="me-2 flex items-center">
                            <a href="#" class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">All <span class="rounded-full bg-blue-50 px-3 py-2 text-xs">2</span></a>
                        
                        </li>
                       
                        <li class="me-2">
                            <a href="#" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Unread <span class="rounded-full px-3 py-2 text-xs">1</span></a>
                        </li>

                       
                    
                    </ul>
            </div>

            <div>

                <a class="p-7 flex items-start gap-4 border-b border-gray-100 hover:bg-gray-50 transition-colors group">

                    <div class="h-2 w-2 rounded-full bg-blue-500 mt-2"></div>
                    <div>
                        <p class="mb-1"><span class="font-bold">New Notification</span> from - School Administrator</p>
                        <p class="text-sm text-gray-400">Now</p>
                    </div>
                    
                </a>

                <a class="p-7 flex items-start gap-4 border-b border-gray-100 bg-gray-50 transition-colors group">

                    <div class="h-2 w-2 rounded-full bg-blue-500 mt-2"></div>
                    <div>
                        <p class="mb-1"><span class="font-bold">New Notification</span> from - School Administrator</p>
                        <p class="text-sm text-gray-400">Now</p>
                    </div>

                </a>

                <div class="px-7 py-2 flex items-start gap-4 border-b border-gray-100">

                   <button class="btn btn-ghost">Mark all as read</button>

                </div>

         
      

    </div>



    </div>

    
</body>
</html>