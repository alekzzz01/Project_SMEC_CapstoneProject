<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Account Registration - SMEC</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>


</head>


<body class="h-screen">
    


    <div class="grid grid-cols-1 xl:grid-cols-2 h-full gap-2">

    <div class="flex flex-col  justify-between p-4 space-y-12">

        
        <a class="flex items-center gap-4" href="../../">
       
            <img src="./../../assets/images/defaultLogo.png" alt="" class="w-10 h-10 object-cover">
         
        </a>

        <div class="m-auto flex flex-col items-center w-full lg:w-[580px] ">
            <div class="space-y-3 w-full text-center">
                <h5 class="text-2xl font-bold">Portal Account Registration</h5>
                <p class="text-slate-500">Access the MIS Portal with Your Personalized Account.</p>
            </div>
    
            <form method="POST" class="mt-8 space-y-6 w-full">

          
                        <div>
                            <label class="text-gray-800 text-sm mb-2 block">Student Number</label>
                            <div class="relative flex items-center">
                            <input name="student_number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your student Number" />
                        
                            </div>
                        </div>

                     

                        <div class="flex flex-wrap items-center justify-end mx-3">
                           

                            <div class="text-sm">
                            <a href="jajvascript:void(0);" class="text-blue-600 hover:underline font-semibold">
                                Need Help?
                            </a>
                            </div>
                        </div>

                        <div class="!mt-8">
                            <button type="submit" name="proceed" class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Proceed to Registration
                            </button>
                        </div>
                        <!-- <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p> -->

                        <div class="flex items-center justify-center">
                            <div class="g-recaptcha" data-sitekey="6LfIZ5EqAAAAAGeXLXbd-FE6FjKxV-VKz4wfSLM2"></div>
                        </div>
            </form>

            
            <?php if (isset($error)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($warning)): ?>
                    <div class="text-red-500 text-sm mt-8"><?php echo $warning; ?></div>
            <?php endif; ?>


        </div>

        <div class="text-xs text-gray-400  flex items-center justify-between">
            <p class="hover:text-black transition-colors">© 2024 Lumix. All Rights Reserved.</p>
            <div class="flex items-center gap-6">
                <a href="" class="hover:text-black transition-colors">Privacy</a>
                <a href="" class="hover:text-black transition-colors">Cookies Policy</a>
            </div>
        </div>


     
    </div>


    <div class="w-full h-full hidden xl:grid justify-center items-center bg-blue-200">
                <img src="../../assets/images/studentPortalAnim.gif" alt="" class=" h-1/2 w-full">
    </div>





    </div>





    
</body>
</html>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
