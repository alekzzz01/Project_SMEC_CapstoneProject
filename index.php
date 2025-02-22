<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sta. Marta Educational Center Inc.</title>


    <link rel="stylesheet" href="./assets/css/styles.css">

    <script src="./assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>




</head>

<body class="relative">


    <?php include 'publicnavbar.php' ?>


    <section class="relative h-[100vh] bg-[url('./assets/images/samplebg.png')] bg-no-repeat bg-cover bg-center">

        <div class="absolute inset-0 bg-gradient-to-r from-black/75 to-black/25"></div>

        <button class="absolute left-5 top-0 bottom-0 m-auto btn btn-ghost  text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>

        <button class="absolute right-5  top-0 bottom-0 m-auto btn btn-ghost  text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        <div class="absolute top-0 bottom-0 m-auto left-48 max-w-7xl flex justify-between items-center">

            <div class="max-w-4xl hidden xl:block">
                <h1 class="text-4xl lg:text-7xl font-bold text-white mb-6" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);">Welcome to Sta. Marta Educational Center Inc.</h1>
                <p class="text-white text-lg" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);">Because here at Sta. Marta Educational Center Inc. "We Make a Difference"</p>


                <div class="mt-16">
                    <a href="" class="text-white bg-blue-700 border-2 border-blue-700 transition-colors py-4 px-12 rounded-md hover:border-blue-800 hover:bg-blue-800 hover:text-white font-semibold">Get Started</a>
                </div>
            </div>
        </div>


    </section>


    <section class="py-24 px-4">

        <div class="mx-auto max-w-7xl">


            <div class="flex flex-col lg:flex-row items-center justify-between mb-6">
                <div class="text-center lg:text-start">
                    <h2 class="text-3xl lg:text-4xl font-semibold mb-4">What’s Happening?</h2>
                    <p class="text-base-content/70">Stay updated with our educational blogs</p>
                </div>

                <img src="./assets/images/happening.gif" alt="" class="h-[180px] w-[180px] object-cover">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5  mb-24">

                <a href="./view/viewEvents.php">
                    <div class="flex gap-4 p-6 bg-blue-50 hover:bg-blue-100 transition-colors rounded-md">
                        <img src="./assets/images/w-1.jfif" alt="" class="w-[100px] h-[100px] object-cover">
                        <div>
                            <p class="text-xl font-semibold mb-2 text-blue-800">Clash of Minds, Balance of Priorities!</p>
                            <p class="line-clamp-2">A battle of wit and wisdom unfolded as Grade 9 - St. Lorenzo Ruiz 🆚 Grade 10 - St. Ignatius of Loyola debated the topic:

                                "Extracurricular Activities Are Just as Important as Academic Performance."</p>
                        </div>
                    </div>
                </a>

                <a href="">
                    <div class="flex gap-4 p-6 bg-blue-50 hover:bg-blue-100 transition-colors rounded-md">
                        <img src="./assets/images/w-2.jfif" alt="" class="w-[100px] h-[100px] object-cover">
                        <div>
                            <p class="text-xl font-semibold mb-2 text-blue-800">38 Years of Blessings!</p>
                            <p class="line-clamp-2">Guided by our Educational Philosophy, Sta. Marta Educational Center, Inc. continues to uphold the belief that education is the key to success, shaping individuals with knowledge, efficiency, and holistic growth.</p>

                        </div>
                    </div>
                </a>

                <a href="">
                    <div class="flex gap-4 p-6 bg-blue-50 hover:bg-blue-100 transition-colors rounded-md" >
                        <img src="./assets/images/w-3.jfif" alt="" class="w-[100px] h-[100px] object-cover">
                        <div>
                            <p class="text-xl font-semibold mb-2 text-blue-800">Battle of Wits! 🗣🔥</p>
                            <p class="line-clamp-2">Grade 7 - St. Camillus and Grade 8 - St. Augustine go head-to-head in a compelling debate on "The use of mobile phones should be allowed during class hours for educational purposes."</p>

                        </div>
                    </div>
                </a>

                <a href="">
                    <div class="flex gap-4 p-6 bg-blue-50 hover:bg-blue-100 transition-colors rounded-md" >
                        <img src="./assets/images/w-4.jfif" alt="" class="w-[100px] h-[100px] object-cover">
                        <div>
                            <p class="text-xl font-semibold mb-2 text-blue-800">Fire Safety Seminar 2025 ✨💙</p>
                            <p class="line-clamp-2">Your child's safety is always our top priority!</p>
                        </div>
                    </div>
                </a>
                
            </div>


            <div class="text-center">
                <a href="" class="text-blue-500 border-2 border-blue-500 transition-colors py-4 px-12 rounded-md hover:border-blue-700 hover:bg-blue-700 hover:text-white font-semibold">Show more</a>
            </div>


        </div>

    </section>




    <div class="py-14 px-4 bg-blue-800 w-full text-center">
        <h2 class="text-3xl lg:text-5xl font-bold text-white">ABOUT US</h2>
    </div>

    <section class="py-24 px-4">
        <div class="grid items-center grid-cols-1 gap-12 lg:gap-0 lg:grid-cols-2 mx-auto max-w-7xl  mb-24">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Our Vision and Mission</h2>
                <p class="text-base-content/70">Creating a world-class learning environment</p>
            </div>

            <div class="space-y-10">
                <div class="flex flex-wrap  gap-4  p-6 bg-blue-50 rounded-md">
                    <div>
                        <p class="text-2xl font-semibold mb-2  text-blue-800">Vision Statement</p>
                        <p>Sta. Marta Educational Center, Inc. is envisioned to uphold its vigor in inspiring a scholastic environment that yields productivity among independently - directed individuals.</p>

                    </div>
                </div>

                <div class="flex flex-wrap  gap-4  p-6 bg-blue-50 rounded-md">
                    <div>
                        <p class="text-2xl font-semibold mb-2  text-blue-800">Mission Statement</p>
                        <p>The Sta. Marta Educational Center, Inc. is committed to exert the fullness of its effort in establishing a community of learners geared with quality and responsive education and turning out graduates who are globally competitive and productive citizens of the nation.</p>

                    </div>
                </div>
            </div>

        </div>

        <div class=" mx-auto max-w-7xl">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-center">Core Values</h2>

            <div class=" grid grid-cols-1 lg:grid-cols-3 gap-10 py-16 mx-auto">

                <div class="flex flex-col items-center">
                    <img src="./assets/images/responsive.svg" alt="" class="w-full h-[200px]">
                    <div class=" mt-5 text-center ">
                        <p class="text-xl font-semibold mb-2 text-blue-800">Responsive</p>
                        <p class="text-base-content/70">Hand in hand with respect, SMECians positively respond clearly and directly to other people and to task set at hand.</p>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <img src="./assets/images/respectful.svg" alt="" class="w-full h-[200px]">
                    <div class=" mt-5 text-center ">
                        <p class="text-xl font-semibold mb-2 text-blue-800">Respectful</p>
                        <p class="text-base-content/70">SMECians acknowledge and respect differences in each other, and provide a safe, supportive environment in which all individuals and staff are valued, and encouraged to engage in open two-way communication.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <img src="./assets/images/thinking.svg" alt="" class="w-full h-[200px]">
                    <div class=" mt-5 text-center ">
                        <p class="text-xl font-semibold mb-2 text-blue-800">Self-Directed</p>
                        <p class="text-base-content/70">SMECians has the ability to regulate and adapt behaviour to the demands of a situation in order to achieve personally chosen goals and values.</p>
                    </div>
                </div>


            </div>

        </div>

    </section>


    <div class="py-14 px-4 bg-blue-800 w-full text-center">
        <h2 class="text-3xl lg:text-5xl font-bold text-white">RECOGNITIONS</h2>
    </div>


    <section class="py-24 px-4 bg-[#fafbfc]">

        <div class="mx-auto max-w-7xl">

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-3 mb-24">

                <div class="space-y-3 bg-white shadow-md rounded-md">
                    <img src="./assets/images/recognitions-1.jfif" alt="" class="h-[300px] w-full object-cover rounded-t-md">
                    <div class="py-6 px-4 border-t border-gray-100">
                        <p class="text-lg font-bold mb-1 line-clamp-1">Awarding of Certificate to the organizers of MATH MONTH! </p>
                        <p class="font-light text-base-content/70 mb-10">6 November 2024</p>
                        <a href="./view/viewRecognitions.php" class=" text-sm bg-blue-500 hover:bg-blue-700 transition-colors py-2 px-4  text-white rounded-md">Read More</a>
                    </div>
                </div>

                <div class="space-y-3 bg-white shadow-md rounded-md">
                    <img src="./assets/images/recognitions-2.jfif" alt="" class="h-[300px] w-full object-cover rounded-t-md">
                    <div class="py-6 px-4 border-t border-gray-100">
                        <p class="text-lg font-bold mb-1">GRABE! ARIBA SMECIANS! ARIBA! 🩵🏊🏽‍♂️🏊🏼‍♀️</p>
                        <p class="font-light text-base-content/70 mb-10">10 December 2024</p>
                        <a href="" class=" text-sm bg-blue-500 hover:bg-blue-700 transition-colors py-2 px-4  text-white rounded-md">Read More</a>
                    </div>
                </div>

                <div class="space-y-3 bg-white shadow-md rounded-md">
                    <img src="./assets/images/recognitions-3.jfif" alt="" class="h-[300px] w-full object-cover rounded-t-md">
                    <div class="py-6 px-4 border-t border-gray-100">
                        <p class="text-lg font-bold mb-1">MEC Mathematics Month 2024</p>
                        <p class="font-light text-base-content/70 mb-10">24 October 2024</p>
                        <a href="" class=" text-sm bg-blue-500 hover:bg-blue-700 transition-colors py-2 px-4  text-white rounded-md">Read More</a>
                    </div>
                </div>

            </div>

            <div class="text-center">
                <a href="" class="text-blue-500 border-2 border-blue-500 transition-colors py-4 px-12 rounded-md hover:border-blue-700 hover:bg-blue-700 hover:text-white font-semibold">Show more</a>
            </div>


        </div>


    </section>



    <div class="py-14 px-4 bg-blue-800 w-full text-center">
        <h2 class="text-3xl lg:text-5xl font-bold text-white">CONTACT</h2>
    </div>

    <section class="py-24 px-4">
        <div class="flex flex-col justify-center items-center gap-12 mx-auto max-w-7xl ">
            <div class="text-center">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Have a question? Reach out to us!</h2>
                <p class="text-base-content/70">Telefax: 8642-2591 • Email: smec_dolmarpasig@yahoo.com • smecdolmarpasig@gmail.com</p>
            </div>

            <form action="" class="w-full lg:w-3/4">
                <div class="space-y-6 ">
                    <div>
                        <p class="text-sm font-semibold mb-2 ml-1">Name</p>
                        <div class="relative flex items-center">
                            <input name="email" type="text" required class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Your Name" />
                        </div>

                    </div>

                    <div>
                        <p class="text-sm font-semibold mb-2 ml-1">Email</p>
                        <div class="relative flex items-center">
                            <input name="email" type="text" required class="bg-gray-50 w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Email" />
                        </div>

                    </div>

                    <div>
                        <p class="text-sm font-semibold mb-2 ml-1">Message</p>
                        <div class="relative flex items-center">
                            <textarea name="" class="bg-gray-50 w-full h-32 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" id="" placeholder="Message"></textarea>

                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="" class=" py-3 px-6 rounded-md text-white text-sm font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Send Message</a>
                </div>
            </form>




        </div>

        <!-- <div class=" mx-auto max-w-7xl">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.854383684177!2d121.0790562760387!3d14.55031847830387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c62a30c9328d%3A0xe7f55d6cec7bcb54!2sSta.%20Marta%20Educational%20Center%2C%20Inc.!5e0!3m2!1sen!2sph!4v1732789028666!5m2!1sen!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div> -->

    </section>





    <?php include 'footer.php' ?>







</body>

</html>