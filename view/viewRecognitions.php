<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>

    <link rel="stylesheet" href="./assets/css/styles.css">

    <script src="./assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>

</head>

<body>

    <div id="navbar" class="px-4 py-2 transition duration-300 bg-blue-800">
        <div class=" flex items-center justify-between">

            <a class="flex items-center gap-4 text-white" href="./">
                <img src="../assets/images/smeclogo.png" alt="" class="w-10 h-10 object-cover bg-white rounded-full">
                <p class="text-2xl font-medium tracking-tighter hidden lg:block">Sta. Marta Educational Center Inc.</p>
            </a>


            <div class="flex items-center ">

                <!-- Initial Items Menu -->
                <ul class="menu menu-horizontal px-1 font-medium hidden lg:flex text-white">

                    <li><a href="../aboutus.php">ABOUT US</a></li>
                    <li><a href="../programs.php">PROGRAMS</a></li>
                    <li><a href="../newsAndevents.php">NEWS & EVENTS</a></li>
                    <li><a href="../schoolServices/">ADMISSIONS</a></li>
                    <li><a href="../portal/">PORTALS</a></li>
                    <li><a href="../auth/login.php">LOGIN</a></li>

                </ul>

                <!-- Small Screen Menu -->
                <div>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden text-white">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                class="inline-block h-5 w-5 stroke-current">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                        <ul
                            tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">

                            <li><a href="aboutus.php">ABOUT US</a></li>
                            <li><a href="programs.php">PROGRAMS</a></li>
                            <li><a href="newsAndevents.php">NEWS & EVENTS</a></li>
                            <li><a href="./schoolServices/">ADMISSIONS</a></li>
                            <li><a href="./portal/">PORTALS</a></li>
                            <li><a href="./auth/login.php">LOGIN</a></li>

                        </ul>
                    </div>

                </div>

            </div>



        </div>









    </div>


    <section class="px-8 py-12 space-y-6">



        <div class="mx-auto max-w-7xl space-y-6">

            <div class="space-y-4">
                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a>Events</a></li>
                        <li><a class="line-clamp-1">GRABE! ARIBA SMECIANS! ARIBA! 🩵🏊🏽‍♂️🏊🏼‍♀️</a></li>
                    </ul>
                </div>

                <div class="flex items-center justify-between">
                    <h1 class="text-4xl font-semibold">GRABE! ARIBA SMECIANS! ARIBA! 🩵🏊🏽‍♂️🏊🏼‍♀️</h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <p class=" text-gray-600">School · <span>Sun April 7,2024</span></p>
                        <p class="text-blue-600 font-medium">Event · <span class="text-gray-600 font-normal">1 min read</span></p>
                    </div>

                    <div class="flex items-center gap-2">
                        <p>Share:</p>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22 12.07C22 6.48 17.52 2 12 2S2 6.48 2 12.07c0 5 3.66 9.13 8.44 9.91v-7.01H7.9v-2.9h2.54v-2.2c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.9h-2.34v7.01C18.34 21.2 22 17.07 22 12.07z" />
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.46 5.95c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37c-.84.5-1.76.85-2.75 1.04a4.28 4.28 0 0 0-7.29 3.9 12.15 12.15 0 0 1-8.8-4.46 4.28 4.28 0 0 0 1.32 5.71 4.28 4.28 0 0 1-1.94-.54v.05a4.29 4.29 0 0 0 3.43 4.2 4.29 4.29 0 0 1-1.94.07 4.3 4.3 0 0 0 4 2.97 8.6 8.6 0 0 1-5.3 1.82c-.34 0-.68-.02-1.02-.06a12.12 12.12 0 0 0 6.56 1.92c7.88 0 12.2-6.53 12.2-12.2v-.56a8.73 8.73 0 0 0 2.16-2.23z" />
                        </svg>


                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>

                    </div>
                </div>
            </div>


            <!-- Body -->

            <div class="space-y-4">

                <p class="text-gray-600">
                    We are going NCR YEY! Dominating Private and Public School Arena 💙
                    <br></br>
                    Johanne Kirstie Briones - 3 Silver and 1 Bronze
                    <br></br>
                    Gabriel Lim - 2 silver
                    <br></br>
                    We are so proud of you!

                </p>

                <p>#PrivateSchool #PreschooltoSeniorHigh #37years</p>

            </div>

            <div class="space-y-4">

                <div>
                    <img src="../assets/images/recognitions-3.jfif" alt="" class="w-full h-96 lg:h-[600px] object-cover rounded-2xl shadow-lg mb-1">
                    <p class="text-center text-gray-400">Source: google.com</p>
                </div>

            </div>




        </div>



    </section>

</body>

</html>