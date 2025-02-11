<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class</title>

    
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


    
                <div class="flex items-center justify-between">
                    <h4 class="text-2xl font-medium mb-1">Class Schedules</h4>

                  

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
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Assigned Room</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Room 1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            <a href="" class="text-teal-700 hover:underline">View Class</a>
                                        </td>
                                    </tr>

                                </tbody>

                        </table>


                </div>
             




    </div>
    
</body>
</html>