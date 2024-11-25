<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event View</title>

    
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


    <?php include './components/navbar.php' ?>

    <div class="container mx-auto py-14 px-4">


    <div class="flex items-center justify-between w-full ">
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li>Events</li>
            <li>Name of Event</li>
        </ul>
        </div>

        

    </div>

    <div class="py-7 px-7 xl:px-20 bg-white rounded-md mt-7">

        <img src="https://a.storyblok.com/f/178900/1920x1080/fc9956de7a/dandadan-key-art-wide.png/m/1200x0/filters:quality(95)format(webp)" alt="" class="w-full h-56 object-cover rounded-md">

        <div class="flex items-center justify-between mt-12">

            <p class="text-sm font-medium text-blue-500">Fri, Nov 15, 2024, 2:00PM</p>

            <div class="flex items-center gap-4">
                <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                </a>

                <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                </svg>
                </a>
            </div>


        </div>

        <div class="space-y-8">
       
            <div class="mt-6">
                <h1 class="font-bold text-4xl">Event 1</h1>
            </div>

            <div class="border-b border-neutral-100"></div>

            <div class="px-4 flex items-center justify-between">

                <div class="flex items-center gap-4">
                    <img src="https://gkids.com/wp-content/uploads/2024/06/DANDA_Poster_RGB_Digital_EpisodeText-1-702x1024.jpg" alt="" class="w-12 h-12 object-cover rounded-full">

                    <div>
                        <p class="text-lg font-semibold mb-1">Organizer Name</p>
                        <p class="text-gray-400 font-light">Organizer</p>
                    </div>
                </div>

                <div>
                    <a class="border border-blue-400 px-4 py-2 rounded-full font-bold text-sm text-blue-500 hover:bg-blue-50">Page</a>
                </div>
            </div>

            <div class="border-b border-neutral-100"></div>

            <div class="flex flex-wrap gap-4">
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5  mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <div>
                        <p class="font-bold">Date and Time</p>
                        <p class="text-gray-400 font-light">Fri, Nov 15, 2024, 2:00 PM – Wed, Nov 20, 2024, 5:00 PM</p>
                    </div>
                </div>

                <div class="border-r border-neutral-100"></div>

                <div class="flex items-start gap-4 ">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>

                    <div>
                        <p class="font-bold">Location</p>
                        <p class="text-gray-400 font-light">Taguig City, Philippines</p>
                    </div>
                </div>


            </div>

        </div>

          

    </div>



    </div>

    
</body>
</html>