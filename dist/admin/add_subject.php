<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>

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

            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="class_section.php">Class List</a></li>
                    <li>Grade 5 (A.Y. 2025-2026)</li>
                    <li>Add Schedule</li>
                </ul>
            </div>


            <div class="border border-gray-300 rounded bg-white mt-7">

                <h1 class="font-semibold p-5 bg-blue-50 rounded-t text-blue-600">Add New Schedule</h1>

                <form class="p-5 space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Section Name</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Section</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800  text-sm font-medium mb-2 block">Subject</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Subject</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Set Start Time</label>
                            <div class="relative flex items-center">
                                <input name="" type="time" class="bg-gray-50 w-full text-gray-800 input input-bordered"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Set End Time</label>
                            <div class="relative flex items-center">
                            <input name="" type="time" class="bg-gray-50 w-full text-gray-800 input input-bordered"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Assign Teacher</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Assign Teacher</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>

                    </div>
                    
                    <div class=" flex items-center justify-end">
                        <button type="submit" name="submitForm" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Add Subject</button>
                    </div>
                        
        
                        

                </form>


            </div>

                
            <div class="border border-gray-300 rounded bg-white mt-3.5">

                <h1 class="text-xl font-bold text-center p-5 bg-blue-50 rounded-t text-blue-600">Grade 5 - Ammolite | A.Y. 2025-2026</h1>

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
                    
                    <div class="border-gray-200 border-b"></div>

                    <div class=" flex items-center justify-center gap-2 mt-6">
                        <button class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md">                       
                            Save Schedule
                        </button>

                        <button class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md">                          
                            Reset
                        </button>
                    </div>
                        

                </div>

                 
            
            </div>





        </div>




    </div>
    
</body>
</html>