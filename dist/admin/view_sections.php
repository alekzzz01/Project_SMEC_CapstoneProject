<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections</title>

    
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
 
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">

 
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

     
    <html data-theme="light"></html>
    
</head>
<body class="flex min-h-screen">

<?php include('./components/sidebar.php'); ?>

<div class="flex flex-col w-full">

<?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">

        <div class="flex justify-between items-center flex-wrap gap-6">

            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="class_section.php">Class List</a></li>
                    <li>Grade 5 (A.Y. 2025-2026)</li>
                </ul>
            </div>



            <button  class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Add Schedule
            </button>


        </div>


        <div class="relative flex items-center w-full mt-7">
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full" >
                                <option value="" disabled selected>Select Section</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
        </div>



        <div class="border border-gray-300 rounded bg-white mt-3.5">

                <div class="p-5 bg-blue-50 rounded-t flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <img src="../../assets/images/smeclogo.png" alt="" class="w-12 h-12 object-cover">
                            <div>
                                <h1 class="font-semibold ">Sta. Marta Educational Center Inc.</h1>
                                <p class="text-xs text-gray-500">Dolmar Subd., Kalawaan Pasig City</p>
                                                            
                            </div>
                           
                        </div>
                       

                        <div>

                        <div class="tooltip" data-tip="Download">
                            <button class="btn btn-sm btn-ghost"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            </button>
                        </div>

                        <div class="tooltip" data-tip="Archive">
                            <button class="btn btn-sm btn-ghost">  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                            </button>
                        </div>

                        </div>
                </div>

                <div class="p-12 flex flex-col items-center justify-center gap-8 ">

                            <div class="text-center">
                                <h1 class="text-3xl font-bold mb-1">Grade 5 - Ammolite</h1>
                                <p class="text-base-content/70 text-sm font-medium">Class List for A.Y. 2025-2026</p>
                            </div>

                            <div class=" flex justify-between gap-[180px]">

                                    <div>
                                        <h1 class="text-lg font-medium mb-1">Boys</h1>
                                        <ol class="list-decimal">
                                        <li>Juan Miguel</li>
                                        <li>Andreigh Jed</li>
                                        <li>Carlos</li>
                                        </ol> 
                                    </div>

                                    <div>
                                        <h1 class="text-lg font-medium mb-1">Girls</h1>
                                        <ol class="list-decimal">
                                        <li>Juan Miguel</li>
                                        <li>Andreigh Jed</li>
                                        <li>Carlos</li>
                                        </ol> 
                                    </div>



                            </div>

                            <p class="text-lg font-semibold">Adviser: Ms. Marie Angela C. Garcia</p>

                </div>

      
   

        </div>


        <div class="border border-gray-300 rounded bg-white mt-3.5">

            <h1 class="text-xl font-semibold text-center p-5 bg-blue-50 rounded-t">Class Schedule A.Y. 2025-2026</h1>
            
            <div class="overflow-hidden p-5">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead>

                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Day</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Teacher</th>
                        </tr>
                        
                        
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">123</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Mathematics</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">8:00 AM - 9:00 AM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">MWF</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Ms. Marie Angela C. Garcia</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">123</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Mathematics</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">8:00 AM - 9:00 AM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">MWF</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Ms. Marie Angela C. Garcia</td>
                        </tr>




                    </tbody>

                </table>




            </div>
        </div>

        





        </div>

        </div>





</div>
    
</body>
</html>