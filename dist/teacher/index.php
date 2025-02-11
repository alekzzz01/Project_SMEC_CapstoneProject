<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    
    
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
<body class="min-h-screen bg-[#f2f5f8]">

    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12 h-full space-y-7">

                <!--  -->

                <div>
                    <h4 class="text-3xl font-bold text-teal-700 mb-1 drop-shadow-sm">Hello, Teacher👋</h4>
                    <p class="text-base-content/70">Let’s make today productive and inspiring!</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

                    <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                        <div class="flex items-center justify-between" >
                                <p class="font-medium">Classes</p>
                                <a href="" class="text-teal-800 hover:underline  text-xs">View Details</a>

                        </div>

                    
                        <div class="flex items-end justify-between  mt-4">
                                <p class="text-4xl font-extrabold mt-2">100</p>
                                <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                15%
                                </p>
                        </div>


                    


                    </div>

                    <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                        <div class="flex items-center justify-between" >
                                <p class="font-medium">Students</p>
                                <a href="" class="text-teal-800 hover:underline text-xs">View Details</a>

                        </div>

                        <div class="flex items-end justify-between  mt-4">
                                <p class="text-4xl font-extrabold mt-2">100</p>
                                <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                15%
                                </p>
                        </div>



                    </div>

                    <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                        <div class="flex items-center justify-between" >
                                <p class="font-medium">Courses</p>
                                <a href="" class="text-teal-800 hover:underline  text-xs">View Details</a>

                        </div>

                        <div class="flex items-end justify-between  mt-4">
                                <p class="text-4xl font-extrabold mt-2">100</p>
                                <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                15%
                                </p>
                        </div>


                    </div>

                    <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                        <div class="flex items-center justify-between" >
                                <p class="font-medium">Events</p>
                                <a href="" class="text-teal-800 hover:underline text-xs">View Details</a>

                        </div>

                        <div class="flex items-end justify-between  mt-4">
                                <p class="text-4xl font-extrabold mt-2">100</p>
                                <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                15%
                                </p>
                        </div>



                    </div>

                </div>


                <!--  -->

                <div>
                    <h4 class="text-2xl font-medium mb-1">Recent Activities</h4>
                    <p class="text-base-content/70">Here are your recent activities</p>               
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    
                        <div class="rounded-2xl p-6 bg-white hover:bg-teal-700 hover:text-white transition-colors shadow border-gray-300 w-full group">

                            <div class="flex items-center justify-between text-xs">
                                <p class="uppercase font-medium">Subject</p>

                                <a href="" class="rounded-full font-medium  px-2 py-1 bg-gray-100 group-hover:text-green-800 group-hover:bg-green-100 inline-flex items-center gap-1 transition-colors">14 Hours</a>
                            </div>

                            <p class="font-bold mt-4"> Activity 1, Hello this is an example for this card.</p>

                            <div class="mt-4">
                                <a href="" class="text-xs font-semibold  border border-amber-600 text-amber-600 transition-colors py-2 px-4 group-hover:bg-amber-500 group-hover:border-white  group-hover:text-white rounded-full uppercase">Read More</a>
                            </div>
                          
                    
                        </div>

                        
                        <div class="rounded-2xl p-6 bg-white hover:bg-teal-700 hover:text-white transition-colors shadow border-gray-300 w-full group">

                            <div class="flex items-center justify-between text-xs">
                                <p class="uppercase font-medium">Subject</p>

                                <a href="" class="rounded-full font-medium  px-2 py-1 bg-gray-100 group-hover:text-green-800 group-hover:bg-green-100 inline-flex items-center gap-1 transition-colors">14 Hours</a>
                            </div>

                            <p class="font-bold mt-4"> Activity 1, Hello this is an example for this card.</p>

                            <div class="mt-4">
                                <a href="" class="text-xs font-semibold  border border-amber-600 text-amber-600 transition-colors py-2 px-4 group-hover:bg-amber-500 group-hover:border-white  group-hover:text-white rounded-full uppercase">Read More</a>
                            </div>
                          
                    
                        </div>

                        
                        <div class="rounded-2xl p-6 bg-white hover:bg-teal-700 hover:text-white transition-colors shadow border-gray-300 w-full group">

                            <div class="flex items-center justify-between text-xs">
                                <p class="uppercase font-medium">Subject</p>

                                <a href="" class="rounded-full font-medium  px-2 py-1 bg-gray-100 group-hover:text-green-800 group-hover:bg-green-100 inline-flex items-center gap-1 transition-colors">14 Hours</a>
                            </div>

                            <p class="font-bold mt-4"> Activity 1, Hello this is an example for this card.</p>

                            <div class="mt-4">
                                <a href="" class="text-xs font-semibold  border border-amber-600 text-amber-600 transition-colors py-2 px-4 group-hover:bg-amber-500 group-hover:border-white  group-hover:text-white rounded-full uppercase">Read More</a>
                            </div>
                          
                    
                        </div>

                </div>


                <!--  -->
                <div class="flex items-center justify-between">
                    <h4 class="text-2xl font-medium mb-1">Classroom</h4>

                    <a href="advisory_Class.php" class="btn rounded bg-teal-700 hover:bg-teal-600 text-white"> 
                  
                   
                    View Advisory Class
                    </a>

                </div>

                <div class="space-y-4">
                        <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">

                                 <select class="select select-bordered select-sm">
                                        <option value="">All Status</option>
                                        <option value="">Math</option>
                                        <option value="">Science</option>
                                        <option value="">English</option>
                                    </select>

                                    
                                   <select class="select select-bordered select-sm">
                                        <option value="">All Status</option>
                                        <option value="">Math</option>
                                        <option value="">Science</option>
                                        <option value="">English</option>
                                    </select>

                                </div>

                                <label class="input input-sm input-bordered flex items-center gap-2">
                                <input type="text" class="grow" placeholder="Search" />
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 16 16"
                                    fill="currentColor"
                                    class="h-4 w-4 opacity-70">
                                    <path
                                    fill-rule="evenodd"
                                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                                    clip-rule="evenodd" />
                                </svg>
                                </label>

                        </div>

                        <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">

                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Class</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Students</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Subject</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Section</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Duration</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">UI/UX</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">30</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Design</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Section 1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">1hr</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            <a href="" class="text-teal-700 hover:underline">View</a>
                                        </td>
                                    </tr>

                                </tbody>

                        </table>


                </div>
             
                        
                


                

    </div>



    
</body>
</html>