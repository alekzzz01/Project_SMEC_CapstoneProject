<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment</title>

       
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>
   

    <!--JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

     
     
</head>
<body>


<?php include './layouts/navbar.php' ?>

  
    <div class="py-16 px-4 lg:px-12 border-b border-gray-100 "> 
            
            <div class="space-y-6 container mx-auto ">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-extrabold mb-1">Enrollment Form</h2>

                    <p class="text-gray-400 font-light">Enrollment for A.Y. 2022 - 2023 is open!</p>
                </div>
               
            </div>

    </div>

    <div class="py-16 px-4 lg:px-12"> 
            
            <div class="container mx-auto space-y-7">

                <form action="" class="space-y-6">
                    
                        <h1 class="text-lg font-bold">Personal Details <span class="text-red-500">*</span></h1>
                        <!-- Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Name</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"readonly />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">First Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"readonly />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly  />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">LRN</p>
                                </div>
                            </div>
                        </div>

                        <!-- Birthdate, Gender and Year Level -->
                        <div>
                        
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                
                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-6 block">Birth Date</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="date" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" readonly/>
                                
                                    </div>
                                
                                </div>

                                <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Gender</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly  />
                                
                                    </div>
                                
                                </div>

                              

                                <div>
                                    <label class="text-gray-800 text-sm font-medium mb-6 block">Mobile Number</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly />
                                
                                    </div>
                                
                                </div>


                            

                        
                            </div>
                        </div>

                        <!-- Parent/Guardian Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Parent/Guardian Name</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly/>
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">First Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"readonly/>
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" readonly  />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                                </div>

                                <div>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  readonly />
                                
                                    </div>
                                    <p class="text-sm font-light mt-1 ml-1">Contact Number</p>
                                </div>
                            </div>
                        </div>


                </form>

                <div class="border-b border-gray-100"></div>

                <form action="" class="space-y-6">
                    
                    <h1 class="text-lg font-bold">Academic <span class="text-red-500">*</span></h1>

                    <!-- Student Type -->

                    <div class="flex flex-col gap-4">

                            <div class="flex items-center gap-3">
                                    <input type="radio" name="radio-1" class="radio radio-info" />
                                    <span>New Student</span>
                            </div>

                            <div class="flex items-center gap-3">
                                    <input type="radio" name="radio-1" class="radio radio-info" />
                                    <span>Transferee</span>
                            </div>

                            <div class="flex items-center gap-3">
                                    <input type="radio" name="radio-1" class="radio radio-info" />
                                    <span>Returning Student</span>
                            </div>

                    </div>
                    
            
                    <!-- Grade level, School year, Last School Attended -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">

                        
                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Grade Level</label>
                                <div class="relative flex items-center">
                                    <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="" disabled selected>Select grade level</option>
                                        <option value="grade-1">Grade 1</option>
                                        <option value="grade-2">Grade 2</option>
                                        <option value="grade-3">Grade 3</option>
                                        <option value="grade-4">Grade 4</option>
                                        <option value="grade-5">Grade 5</option>
                                        <option value="grade-6">Grade 6</option>
                                        <option value="grade-7">Grade 7</option>
                                        <option value="grade-8">Grade 8</option>
                                        <option value="grade-9">Grade 9</option>
                                        <option value="grade-10">Grade 10</option>
                                        <option value="grade-11">Grade 11</option>
                                        <option value="grade-12">Grade 12</option>
                                    </select>
                                </div>
                            
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">School Year</label>
                                <div class="relative flex items-center">
                                    <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                        <option value="" disabled selected>Select school year</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2024-2025">2024-2025</option>
                                    </select>
                                </div>
                            
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Last School Attended</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  readonly />
                                
                                    </div>
                               
                            </div>
                            


                       

                        

                    
                    </div>

                    <!-- File Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Birth Certificate</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="file" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
    
                                    </div>
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Report Card</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="file" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
    
                                    </div>
                            </div>

                            <div>
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Good Moral Certificate</label>
                                    <div class="relative flex items-center">
                                    <input name="email" type="file" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
    
                                    </div>
                            </div>

                            <p class="text-gray-400 text-sm">*Note: New student should submit their birth certificate, report card and good moral certificate.</p>



                    </div>

                    <div class="border-gray-100 border-b"></div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" checked="checked" class="checkbox" />
                            <p class="font-medium text-sm">I confirm that the information provided is accurate.</p>
                        </div>
                        <p class="text-gray-400 text-sm">By checking this box, you agree to our <a class="text-black hover:underline">Terms and Conditions</a> and <a class="text-black hover:underline">Privacy Policy.</a></p>
                    </div>

                    


                </form>

                <div class="border-gray-100 border-b"></div>

                <!-- Submit Form -->
                <div class=" flex items-center justify-end">
                    <button class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit Form</button>
                </div>

                <!-- <button class="btn" onclick="my_modal_5.showModal()">open modal</button> -->



            </div>

    </div>

    <!-- Modal For Success of submission of the form -->
   
    <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-lg font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
            Enrollment Submitted Successfully!</h3>

            <p class="py-4 text-gray-400 text-sm">Thank you for submitting your enrollment form. Please follow these next steps:</p>


            <!-- Instructions -->
            <div className="py-4">
                <ol class="list-decimal list-inside space-y-2">
                <li>Visit the school registrar&apos;s office within the next 5 business days.</li>
                <li>Bring the following documents:
                    <ul class="list-disc list-inside ml-4 mt-1">
                    <li>Valid ID</li>
                    <li>Original and photocopy of your diploma or transcript</li>
                    <li>2 recent passport-sized photos</li>
                    </ul>
                </li>
                <li>Be prepared to pay the enrollment fee at the cashier&apos;s office.</li>
                <li>Collect your student ID and class schedule from the registrar.</li>
                </ol>
            </div>

      

            <div class="modal-action">
            <form method="dialog">
                <!-- if there is a button in form, it will close the modal -->
                <button class="btn">Close</button>
            </form>
            </div>
        </div>
    </dialog>


        

  

    
</body>
</html>



