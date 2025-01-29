<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>

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


    <!-- DataTables CSS (Hover Styling) -->
    <link href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" rel="stylesheet">
  
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>


</head>
<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

    <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">

          

            <div class="border border-gray-300 rounded bg-white">

                <h1 class="font-semibold p-5 bg-blue-50 rounded-t text-blue-600">Add Subject</h1>

                <form class="p-5 space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Subject Name</label>
                            <div class="relative flex items-center">
                            <input name="" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="Enter Subject Name"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Subject Code</label>
                            <div class="relative flex items-center">
                            <input name="" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="Enter Subject Code"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Grade Level</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Grade Level</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Subject Type</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Subject Type</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Track/Strand</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Track/Strand</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>


                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Total Number of Hours Per Week</label>
                            <div class="relative flex items-center">
                            <input name="" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="8"/>
                            </div>
                                       
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Description</label>
                            <div class="relative flex items-center">
                            <input name="" type="text" class="bg-gray-50 w-full text-gray-800 input input-bordered" placeholder="Enter subject description"/>
                            </div>
                                       
                        </div>

                    </div>
                    
                    <div class=" flex items-center justify-center">
                        <button type="submit" name="submitForm" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit</button>
                    </div>
                        

                        

                </form>


            </div>

            <div>
                    <h1 class="text-lg font-medium mb-1 mt-7">View All Subjects</h1>
            </div>

            <div class="border border-gray-300 rounded bg-white mt-3.5 p-6">
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Filter Grade Level</label>
                            <select name="gradelevel" id="gradelevel" required class="select select-bordered w-full bg-gray-50" >
                                <option value="" disabled selected>Select Track/Strand</option>
                                <option value="Grade-1">Ammolite</option>
                                <option value="Grade-1">Anatase</option>
                               
        
                            </select>
                        </div>

            </div>


            <div class="border border-gray-300 rounded bg-white mt-3.5 p-6">

                <table id="example" class="min-w-full divide-y divide-gray-200">
                    <thead class="border border-gray-300 text-sm">
                        <tr>
                            <th class="py-3 px-4 text-left">School Year ID</th>
                            <th class="py-3 px-4 text-left">School Year</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200 border border-gray-300">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">2024-2025</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Open</td>
                 
                            <td>
                                    
                                    <form method="POST" >
                                        <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                    
                                        <button type='submit' name='approve' class='text-green-600 text-sm hover:underline'>[Open]</button>
                                        <button type='submit' name='reject' class='text-red-500 text-sm hover:underline'>[Close]</button>
                                                         
                                    </form>

                                
                                
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>


        </div>


    </div>

    
</body>
</html>

<script>
    $(document).ready(function () {
        var table = $('#example').DataTable ({
            searching: true, // Enables the search box
            paging: true,    // Enables pagination
            ordering: true,  // Enables column sorting
            info: true,      // Displays table information
        });

    });
</script>