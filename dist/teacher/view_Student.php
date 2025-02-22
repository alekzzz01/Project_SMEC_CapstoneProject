<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>



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

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12 h-full">


        <div class="grid grid-cols-1 gap-7 lg:grid-cols-3">

            <!-- 1st Column -->

            <div class="col-span-2">

                <div class="breadcrumbs text-sm mb-3.5">
                    <ul>

                        <li><a href="advisory_Class.php">Advisory Class</a></li>
                        <li>View Student</li>
                    </ul>
                </div>

                <h1 class="text-lg font-medium mb-3.5">Report on Learning Progress and Achievement</h1>

            
                <div class="rounded bg-teal-100 p-4 mb-7 space-y-2 shadow">
                    <h2 class="text-teal-900 font-semibold text-xl">GRADE 9 - LAPIZ </h2>
                    <h1 class="font-extrabold text-4xl text-teal-900">YUL GIBSON C. GARCIA</h1>
                    <p class="text-teal-800 text-sm italic">Adviser: Ms. Victoria O. Panganiban</p>
                </div>


                <div role="tablist" class="tabs tabs-bordered bg-white p-4 border border-gray-200 rounded mb-7 w-full">

                    <!-- Student Grades -->
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Grade" checked="checked" />
                    <div role="tabpanel" class="tab-content pt-6">
                        <table>
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2 text-left">Subject</th>
                                    <th class="border px-4 py-2 text-left">1st Quarter</th>
                                    <th class="border px-4 py-2 text-left">2nd Quarter</th>
                                    <th class="border px-4 py-2 text-left">3rd Quarter</th>
                                    <th class="border px-4 py-2 text-left">4th Quarter</th>
                                    <th class="border px-4 py-2 text-left">Final Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="border px-4 py-2 bg-teal-100">Math</td>
                                    <td class="border px-4 py-2 bg-teal-100">90</td>
                                    <td class="border px-4 py-2 bg-teal-100">85</td>
                                    <td class="border px-4 py-2 bg-teal-100">80</td>
                                    <td class="border px-4 py-2 bg-teal-100">85</td>
                                    <td class="border px-4 py-2 bg-teal-100">85</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2">Science</td>
                                    <td class="border px-4 py-2">85</td>
                                    <td class="border px-4 py-2">80</td>
                                    <td class="border px-4 py-2">85</td>
                                    <td class="border px-4 py-2">80</td>
                                    <td class="border px-4 py-2">82.5</td>

                                </tr>

                                <tr>
                                    <td class="border px-4 py-2 bg-teal-100">English</td>
                                    <td class="border px-4 py-2 bg-teal-100">90</td>
                                    <td class="border px-4 py-2 bg-teal-100">85</td>
                                    <td class="border px-4 py-2 bg-teal-100">80</td>
                                    <td class="border px-4 py-2 bg-teal-100">85</td>
                                    <td class="border px-4 py-2 bg-teal-100">85</td>
                                </tr>



                            </tbody>

                        </table>

                    </div>

                    <!-- Behavior Log -->
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Behavior" />
                    <div role="tabpanel" class="tab-content pt-6">
                        <table>
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2 text-left">Core Value</th>
                                    <th class="border px-4 py-2 text-left">Behavior Statements</th>
                                    <th class="border px-4 py-2 text-left">Q1</th>
                                    <th class="border px-4 py-2 text-left">Q2</th>
                                    <th class="border px-4 py-2 text-left">Q3</th>
                                    <th class="border px-4 py-2 text-left">Q4</th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="border px-4 py-2 bg-teal-100">Maka-Diyos</td>
                                    <td class="border px-4 py-2 bg-teal-100">Expresses one's spiritual beliefs while respecting the spiritual beliefs of others</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">SO</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2">Maka-Tao</td>
                                    <td class="border px-4 py-2">Shows adherence to ethical principles by upholding truth</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">SO</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2 bg-teal-100">Makakalikasan</td>
                                    <td class="border px-4 py-2 bg-teal-100">Is sensitive to individual, social and cultural differences Demonstrates contributions towards solidarity</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">AO</td>
                                    <td class="border px-4 py-2 bg-teal-100">SO</td>
                                </tr>

                                <tr>
                                    <td class="border px-4 py-2">Makabansa</td>
                                    <td class="border px-4 py-2">Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen </td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">AO</td>
                                    <td class="border px-4 py-2">SO</td>
                                </tr>





                            </tbody>

                        </table>
                    </div>

                    <!-- Comments -->
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Comments" />
                    <div role="tabpanel" class="tab-content p-10">Tab content 3</div>


                </div>

                <!-- learner Progress -->
                <div class="p-4 border border-gray-200 rounded bg-white mb-3.5">
                    <h1 class="font-bold mb-6 text-center">LEARNER PROGRESS AND ACHIEVEMENT</h1>


                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Descriptors</th>
                                <th class="px-4 py-2 text-left">Grading Scale</th>
                                <th class="px-4 py-2 text-left">Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="px-4 py-2">Outstanding</td>
                                <td class="px-4 py-2">90 - 100</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Very Satisfactory</td>
                                <td class="px-4 py-2">85 - 89</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Satisfactory</td>
                                <td class="px-4 py-2">80 - 84</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Fairly Satisfactory</td>
                                <td class="px-4 py-2">75 - 79</td>
                                <td class="px-4 py-2">Passed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">Did not meet Expectations</td>
                                <td class="px-4 py-2">Below 75</td>
                                <td class="px-4 py-2">Failed</td>
                            </tr>

                        </tbody>

                    </table>


                </div>
                <!-- Observed Value -->
                <div class="p-4 border border-gray-200 rounded bg-white">
                    <h1 class="font-bold mb-6 text-center">OBSERVED VALUE</h1>


                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Marking</th>
                                <th class="px-4 py-2 text-left">Non-Numerical Rating</th>

                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="px-4 py-2">AO</td>
                                <td class="px-4 py-2">Always Observed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">SO</td>
                                <td class="px-4 py-2">Sometimes Observed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">RO</td>
                                <td class="px-4 py-2">Rarely Observed</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2">NO</td>
                                <td class="px-4 py-2">Not Observed</td>
                            </tr>


                        </tbody>

                    </table>


                </div>

            </div>

            <!-- 2nd Column -->
            <div class="p-6 bg-white rounded-md border border-gray-200 space-y-3.5 col-span-1">

                <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="" class="h-[330px] w-full object-cover rounded" />


                <div>
                    <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Name</p>
                    <div class="relative flex items-center">
                        <input name="grade-level" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-teal-50" readonly value="YUL GIBSON C. GARCIA" />

                    </div>

                </div>


                <div>
                    <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Grade Level</p>
                    <div class="relative flex items-center">
                        <input name="grade-level" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-teal-50" readonly />

                    </div>

                </div>

                <div>
                    <p class="text-sm font-light mb-1 ml-1 text-base-content/70">Academic Year</p>
                    <div class="relative flex items-center">
                        <input name="first_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md  bg-teal-50" readonly />

                    </div>

                </div>






            </div>



        </div>

    </div>











</body>

</html>