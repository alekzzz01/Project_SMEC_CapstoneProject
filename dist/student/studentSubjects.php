<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   
</head>
<body class="bg-gray-100 h-full flex flex-col">


    
    <?php include './components/navbar.php' ?>


    <div class="container mx-auto py-14 px-4 lg:px-12">


    <div class="flex items-center justify-between w-full ">
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li>Subjects</li>
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

    <div class="p-7 bg-white rounded-md space-y-4 mt-7">
            <?php include './tables/gradesTable.php' ?>
      

    </div>



    </div>

    
</body>
</html>