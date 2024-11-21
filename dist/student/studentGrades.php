<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades</title>

    
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

    <div class="container mx-auto py-14 px-4">


    <div class="flex items-center justify-between w-full ">
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li>Grades</li>
        </ul>
        </div>

            
 
       
        <div class="relative max-w-sm">
                <input class="w-full py-2 px-4 border border-neutral-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="search" placeholder="Search">
                <button class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-gray-100 border border-neutral-200 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.795 13.408l5.204 5.204a1 1 0 01-1.414 1.414l-5.204-5.204a7.5 7.5 0 111.414-1.414zM8.5 14A5.5 5.5 0 103 8.5 5.506 5.506 0 008.5 14z" />
                </svg>
            </button>
        </div>



       

    </div>

    <div class="p-7 bg-white rounded-md space-y-6 mt-7">

            <div class="border border-gray-100 p-4 space-y-2">
                    <p class="text-xl font-semibold">Grade 1 - First Quarter</p>
                    <p>A.Y. 2023-2024</p>
                    <div class="flex items-center justify-between">
                        <p class="px-3 py-2 bg-blue-50 text-blue-600 rounded-sm">Section A</p>

                        <button class="btn btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </button>

                    </div>
            </div>

            
            <div class="border border-gray-100 p-4 space-y-2">
                    <p class="text-xl font-semibold">Grade 1 - Second Quarter</p>
                    <p>A.Y. 2023-2024</p>
                    <div class="flex items-center justify-between">
                        <p class="px-3 py-2 bg-blue-50 text-blue-600 rounded-sm">Section A</p>

                        <button class="btn btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </button>

                    </div>
            </div>

            
            <div class="border border-gray-100 p-4 space-y-2">
                    <p class="text-xl font-semibold">Grade 1 - Third Quarter</p>
                    <p>A.Y. 2023-2024</p>
                    <div class="flex items-center justify-between">
                        <p class="px-3 py-2 bg-blue-50 text-blue-600 rounded-sm">Section A</p>

                        <button class="btn btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </button>

                    </div>
            </div>

    </div>



    </div>

    
</body>
</html>