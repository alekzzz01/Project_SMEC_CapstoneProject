<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Status</title>

    
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


   
        
        <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li>Enrollment Status</li>
        </ul>
        </div>


    <div class="p-7 bg-white rounded-md mt-7 flex flex-col lg:flex-row gap-7">


        <!-- Navigation -->
        <div class="space-y-4 col-span-1 max-w-72 w-full">

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z" clip-rule="evenodd" />
                    </svg>

                    <a>Preferences</a>
                </div>


                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>

                    <a>Enrollment Status</a>
                </div>

            
                <div class="border-b border-gray-100"></div>

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                    </svg>

                    <a href="profile.php">Basic Information</a>
                </div>

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z" clip-rule="evenodd" />
                    </svg>


                    <a href="profile.php">Notifications</a>
                </div>

                <div class="flex items-center gap-3 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>

                    <a href="profile.php">Password and Security</a>
                </div>



            
        </div>

        <!-- Contents -->
        <div class="col-span-2 flex flex-col gap-7 w-full">

            <!-- Basic Information -->
            <section class="border-gray-100 border rounded" >

                <div class=" px-7 py-6 bg-blue-50">
                    <p class="text-lg font-medium">Enrollment Status</p>
                </div>

                <div class=" px-7 py-6  space-y-12">

                


                   <form action="" class="space-y-6">
                    
                       
                      
                       

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Student Number</label>
                            <div class="relative flex items-center">
                            <input name="name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Input student number"/>
                                    
                            </div>
                        </div>
                        


                        <div class=" flex items-center justify-end">
                            <button class=" py-3 px-14 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Check status</button>
                        </div>

                    </form>


                <!-- Statuses -->

                    <!-- Rejected -->
                    <div>
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 ">Rejected</span>
                        <p class="mt-2 ml-2">We're sorry, but your application has been rejected. Please contact the admissions office for more information.</p>
                    </div>

                     <!-- Pending -->
                     <div>
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 font-medium text-yellow-600 ring-1 ring-inset ring-yellow-500/10 ">Pending</span>
                        <p class="mt-2 ml-2">Your application is currently under review. Please check back later for updates.</p>
                    </div>

                    
                     <!-- Approved -->
                     <div>
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 ">Approved</span>
                        <p class="mt-2 ml-2">Congratulations! Your enrollment application has been approved. Please check your email for further instructions.</p>
                    </div>

                     <!-- Needs Correction -->
                     <div>
                        <span class="inline-flex items-center rounded-md bg-orange-50 px-2 py-1 font-medium text-orange-600 ring-1 ring-inset ring-orange-500/10 ">Needs Correction</span>
                        <p class="mt-2 ml-2">Your application requires some corrections. Please check your email for details on what needs to be updated.</p>
                    </div>


                  
                     
               
                    



                </div>

            </section>

            

        </div>
       

    </div>



    </div>

    
</body>
</html>