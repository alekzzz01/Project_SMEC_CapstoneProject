<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encoding of Grades</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>



    <html data-theme="light">

    </html>

</head>

<body class="min-h-screen bg-[#f2f5f8]">


    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-0 h-full space-y-7">


        <div class="rounded bg-green-100 p-4 mb-7 space-y-2 shadow">
            <h1 class="font-extrabold text-4xl text-green-900">Math</h1>
            <h2 class="text-green-900 font-semibold text-xl">Grade 9 - Lapiz</h2>
            <p class="text-green-800 text-sm italic">Adviser: Ms. Victoria O. Panganiban</p>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">

                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Term</option>
                        <option value="">1st Quarter</option>
                        <option value="">2nd Quarter</option>
                        <option value="">3rd Quarter</option>
                        <option value="">4th Quarter</option>
                        <option value="">Final Grade</option>
                    </select>


                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Order</option>
                        <option value="">Name</option>
                        <option value="">Student No.</option>
                        <option value="">No.</option>
                    </select>

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

                <div class="flex items-center gap-4">

                    <button type="button" class="flex items-center justify-center text-teal-700 hover:text-white border border-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-md text-sm px-4 py-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>

                        Export
                    </button>

                    <button type="button" class="flex items-center justify-center text-white bg-teal-700  border border-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-md text-sm px-4 py-2 ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>


                        Submit Grade
                    </button>



                </div>


            </div>

            <div class=" relative overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Student No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Name</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Course</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 1</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 2</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 3</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Quiz 4</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Seatwork</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Assignment</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Project</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Attendance</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Recitation</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Examination</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Grade</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">20241001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">John Doe</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Grade 9 - Lapiz</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

            <div class=" relative overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white p-4 rounded-2xl shadow border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Student No.</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Name</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Course</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">1st Quarter 50%</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">2nd Quarter 50%</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Final Percent</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Final Grade</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Remark</th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">20241001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">John Doe</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Grade 9 - Lapiz</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" min="0" max="100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="input input-sm w-16 input-bordered" readonly>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" class="input input-sm w-16 input-bordered" readonly>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Section 1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Section 1</td>
                        </tr>
                    </tbody>
                </table>

            </div>







        </div>



    </div>
</body>

</html>