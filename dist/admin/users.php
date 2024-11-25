<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">
     
     <script src="../../assets/js/script.js"></script>

 
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
 
     <script src="https://cdn.tailwindcss.com"></script>
 
     <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">

 
     <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

     
     <html data-theme="light"></html>
    

     

</head>
<body class="flex h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full shadow-xl">

    <!-- Navbar -->

    <?php include('./components/navbar.php'); ?>


        <div class="p-7 bg-gray-50 h-full">
            <h1 class="text-lg font-bold mb-1">User management</h1>
            <p>Manage users and change account roles here.</p>

            <div class=" p-6 bg-white rounded-md mt-7">
                <div>
                    <?php include('./tables/userTable.php'); ?>
                </div>

            </div>

        </div>



    </div>

    <!-- Modals -->
    <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add new user</h3>
            <form action="" class="py-4 grid grid-cols-2 gap-3">
              
                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">First Name</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter First Name" />
                        
                            </div>
                    </div>

                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Last Name</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Last Name" />
                        
                            </div>
                    </div>

                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Email</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter email" />
                        
                            </div>
                    </div>


                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Role</label>
                            <div class="relative flex items-center">
                            <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                    <option value="" disabled selected>Select role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Student">Student</option>
                                  
                                </select>
                        
                            </div>
                    </div>


                    
                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                    </div>

                  
            </form>

            <div>
                      
                 <button class="flex items-center gap-1 font-medium text-sm  text-white border-2 border-blue-600 hover:border-blue-700 bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-1">
                         
                          Generate password
                </button>
                    
            </div>

           
            <div class="modal-action">
            <form method="dialog">
              
                <button class="btn">Close</button>
                <button class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Add User</button>
            </form>
            </div>
        </div>
    </dialog>
    
</body>
</html>

