// Check if user is logged in and otp is verified
if (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
    // Redirect to OTP page if OTP hasn't been verified yet
    header('Location: ../../auth/otpAuth.php');
    exit();
}


if (isset($_GET['teacher_id'])) {
    $_SESSION['teacher_id'] = $_GET['teacher_id'];
}

// Use the teacher_id from either the session or GET parameter
$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : (isset($_GET['teacher_id']) ? htmlspecialchars($_GET['teacher_id']) : '');
?>

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



    <html data-theme="light">

    </html>

</head>

<body class="min-h-screen bg-[#f2f5f8]">

    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-0 h-full space-y-7">

        <!--  -->



        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

            <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                <div class="flex items-center justify-between">
                    <p class="font-medium">Classes</p>
                    <a href="" class="text-teal-800 hover:underline  text-xs">View Details</a>

                </div>


                <div class="flex items-end justify-between  mt-4">
                    <p class="text-4xl font-extrabold mt-2">100</p>
                    <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>

                    </p>
                </div>





            </div>

            <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                <div class="flex items-center justify-between">
                    <p class="font-medium">Students</p>
                    <a href="" class="text-teal-800 hover:underline text-xs">View Details</a>

                </div>


            </div>

            <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                <div class="flex items-center justify-between">
                    <p class="font-medium">Courses</p>
                    <a href="" class="text-teal-800 hover:underline  text-xs">View Details</a>

                </div>

                <div class="flex items-end justify-between  mt-4">
                    <p class="text-4xl font-extrabold mt-2">100</p>
                    <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </p>
                </div>




            <div class="rounded-2xl p-4 bg-white shadow border-gray-300 w-full">
                <div class="flex items-center justify-between">
                    <p class="font-medium">Events</p>
                    <a href="" class="text-teal-800 hover:underline text-xs">View Details</a>

                </div>

                <div class="flex items-end justify-between  mt-4">
                    <p class="text-4xl font-extrabold mt-2">100</p>
                    <p class="rounded-full p-2 text-teal-800 bg-teal-100 inline-flex items-center gap-1 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>

                    </p>
                </div>



            </div>

        </div>


        <!--  -->

        <div>
            <h4 class="text-2xl font-medium mb-1">Recent Activities</h4>
            <p class="text-base-content/70">Here are the recent activities</p>
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

            <a href="advisory_Class.php?teacher_id=<?php echo htmlspecialchars($teacher_id); ?>" class="btn rounded bg-teal-700 hover:bg-teal-600 text-white">
                View Advisory Class
            </a>

        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">

                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Sort by Subject</option>
                        <option value="asc">A-Z (Alphabetical)</option>
                        <option value="desc">Z-A (Reverse Alphabetical)</option>
                    </select>

                    <select class="select select-bordered select-sm">
                        <option disabled selected value="">Sort by Section</option>
                        <option value="asc">A-Z (Alphabetical)</option>
                        <option value="desc">Z-A (Reverse Alphabetical)</option>
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
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">UI/UX</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">30</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Design</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Section 1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">1hr</td>
                    </tr>

                </tbody>

            </table>


        </div>







    </div>




</body>

</html>