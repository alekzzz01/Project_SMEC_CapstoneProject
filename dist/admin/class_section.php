<?php 

session_start();
include '../../config/db.php';




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section List</title>

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

   
        <div class="flex items-center justify-between flex-wrap gap-6">

            
            <div>
                    <h1 class="text-lg font-medium mb-1">Section</h1>
                
            </div>

      
            <div class="flex items-center justify-between gap-1 lg:gap-3 flex-wrap">

                <select name="role_type" class="select select-bordered select-sm">
                              <option value="">Filter by Year</option> <!-- Option to clear the filter -->
                              <option value="Admin">Admin</option>
                              <option value="Teacher">Teacher</option>
                              <option value="Student">Student</option>
                </select>

                
                <select name="role_type" class="select select-bordered select-sm ">
                              <option value="">Sort by Year</option> <!-- Option to clear the filter -->
                              <option value="Admin">Admin</option>
                              <option value="Teacher">Teacher</option>
                              <option value="Student">Student</option>
                </select>


                <div class="border border-r h-6"></div>

                <button onclick="add_section.showModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Add Section
                </button>

            
                <!-- 
                <button class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>

                    Archive
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>

                    Delete
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>

                    Restore
                </button> -->

            
            </div>

        </div>

        <div class="my-7 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="p-6 bg-white rounded-t-md shadow border-b-4 border-blue-600">

                    <p class="font-bold text-lg mb-1">Grade 1</p>
                    <p class="text-base-content/70 text-sm font-medium mb-6">A.Y. 2025-2026</p>

                    <div class="flex items-center justify-between">

                    
                        <p class="px-3 py-1.5 rounded-full hover:bg-gray-50 border-2 border-gray-300 text-base-content/70 font-medium text-sm transition-colors inline-flex items-center gap-1.5"><span class="font-semibold text-lg">3 </span>Total Sections</p>

                        <a href="" class="btn "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        </a>

                    </div>

                    

                </div>

                <div class="p-6 bg-white rounded-t-md shadow border-b-4 border-yellow-600">

                    <p class="font-bold text-lg mb-1">Grade 2</p>
                    <p class="text-base-content/70 text-sm font-medium mb-6">A.Y. 2025-2026</p>

                    <div class="flex items-center justify-between">


                        <p class="px-3 py-1.5 rounded-full hover:bg-gray-50 border-2 border-gray-300 text-base-content/70 font-medium text-sm transition-colors inline-flex items-center gap-1.5"><span class="font-semibold text-lg">4 </span>Total Sections</p>

                        <a href="" class="btn "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        </a>

                    </div>



                </div>

                <div class="p-6 bg-white rounded-t-md shadow border-b-4 border-green-600">

                    <p class="font-bold text-lg mb-1">Grade 3</p>
                    <p class="text-base-content/70 text-sm font-medium mb-6">A.Y. 2025-2026</p>

                    <div class="flex items-center justify-between">


                        <p class="px-3 py-1.5 rounded-full hover:bg-gray-50 border-2 border-gray-300 text-base-content/70 font-medium text-sm transition-colors inline-flex items-center gap-1.5"><span class="font-semibold text-lg">4 </span>Total Sections</p>

                        <a href="" class="btn "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        </a>

                    </div>



                </div>

                <div class="p-6 bg-white rounded-t-md shadow border-b-4 border-purple-600">

                    <p class="font-bold text-lg mb-1">Grade 4</p>
                    <p class="text-base-content/70 text-sm font-medium mb-6">A.Y. 2025-2026</p>

                    <div class="flex items-center justify-between">


                        <p class="px-3 py-1.5 rounded-full hover:bg-gray-50 border-2 border-gray-300 text-base-content/70 font-medium text-sm transition-colors inline-flex items-center gap-1.5"><span class="font-semibold text-lg">4 </span>Total Sections</p>

                        <a href="" class="btn "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        </a>

                    </div>



                </div>






        </div>




    </div>




</div>



<dialog id="add_section" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add new section</h3>

            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <form action="" class="py-4 grid grid-cols-2 gap-6" method="POST">

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Grade Level</label>
                        <div class="relative flex items-center">
                            <select name="gradelevel" id="gradelevel" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" onchange="toggleStudentNumberField()">
                                <option value="" disabled selected>Choose Grade Level</option>
                                <option value="Grade-1">Grade 1</option>
                                <option value="Grade-2">Grade 2</option>
                                <option value="Grade-3">Grade 3</option>
                                <option value="Grade-4">Grade 4</option>
                                <option value="Grade-5">Grade 5</option>
                                <option value="Grade-6">Grade 6</option>
                                <option value="Grade-7">Grade 7</option>
                                <option value="Grade-8">Grade 8</option>
                                <option value="Grade-9">Grade 9</option>
                                <option value="Grade-10">Grade 10</option>
                                <option value="Grade-11">Grade 11</option>
                                <option value="Grade-12">Grade 12</option>
        
                            </select>
                        </div>
                    </div>

                    
        
                    <div id="section_name_field" >
                            <label class="text-gray-800 text-sm mb-2 block">Section Name</label>
                            <div class="relative flex items-center">
                            <input name="section_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Section Name" />
                        
                            </div>
                    </div>

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Track/Strand</label>
                        <div class="relative flex items-center">
                            <select name="track" id="gradelevel" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" onchange="toggleStudentNumberField()">
                                <option value="" disabled selected>Choose Track/Strand</option>
                                <option value="elementary">Elementary</option>
                                <option value="highschool">High-School</option>
                                <option value="ABM">ABM Track</option>
                                <option value="GAS">GAS Track</option>
                               
        
                            </select>
                        </div>
                    </div>


                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Academic Year</label>
                        <div class="relative flex items-center">
                            <select name="track" id="gradelevel" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" onchange="toggleStudentNumberField()">
                                    <option value="">Select school year</option>
                                            <?php
                                                // Fetch distinct school years with status 'open' for the filter dropdown
                                            $schoolYearQuery = "SELECT DISTINCT school_year FROM school_year WHERE status = 'open' ORDER BY school_year ASC";
                                            $schoolYearResult = $connection->query($schoolYearQuery);
                                            if ($schoolYearResult->num_rows > 0) {
                                                while ($row = $schoolYearResult->fetch_assoc()) {
                                                    echo "<option value='{$row['school_year']}'>{$row['school_year']}</option>";
                                                }
                                            }
                                        ?>
        
                            </select>
                        </div>
                    </div>


                    
                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Class Adviser</label>
                        <div class="relative flex items-center">
                            <select name="gradelevel" id="gradelevel" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" onchange="toggleStudentNumberField()">
                                <option value="" disabled selected>Assign Adviser</option>
                                <option value="Grade-1">Grade 1</option>
                              
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Number of Students</label>
                        <div class="relative flex items-center">
                            <input name="section_name" type="number" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Number of Students" />
                        
                        </div>
                    </div>

                    <div class="modal-action col-span-2">
                    
                    <button type="submit" name="createUser" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Save Section</button>
                
                    </div>
     

            </form>

        </div>
           
          
</dialog>




</body>
</html>