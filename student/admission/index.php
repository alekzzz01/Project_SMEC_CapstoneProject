<?php
session_start();

include '../../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $year_level = $_POST['year_level'];
    $parent_first_name = $_POST['parent_first_name'];
    $parent_middle_initial = $_POST['parent_middle_initial'];
    $parent_last_name = $_POST['parent_last_name'];
    $region = $_POST['region_text'];
    $province = $_POST['province_text'];
    $city = $_POST['city_text'];
    $barangay = $_POST['barangay_text'];
    $zip_code = $_POST['zip_code'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $emergency_first_name = $_POST['emergency_first_name'];
    $emergency_last_name = $_POST['emergency_last_name'];
    $relationship = $_POST['relationship'];

    $query = "INSERT INTO admission_form 
    (first_name, middle_initial, last_name, birth_date, gender, year_level, 
    parent_first_name, parent_middle_initial, parent_last_name, region, province, city, barangay, zip_code, 
    phone, email, emergency_first_name, emergency_last_name, relationship, created_at) 
    VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param("sssssssssssssssssss", 
        $first_name, $middle_initial, $last_name, $birth_date, $gender, 
        $year_level, $parent_first_name, $parent_middle_initial, $parent_last_name, $region, 
        $province, $city, $barangay, $zip_code, $phone, $email, $emergency_first_name, 
        $emergency_last_name, $relationship);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Your admission form has been successfully submitted.";
            header("Location: ./index.php");
            exit();
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $connection->error;
    }

    $connection->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Form</title>

    <link rel="stylesheet" href="../../assets/css/styles.css">
     
 
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


    <div id="navbar" class="px-4 py-2 fixed w-full top-0 left-0 z-10 transition duration-300">
        <div class=" flex items-center justify-between">

            <a class="flex items-center gap-4 text-white" href="./">
                <img src="../../assets/images/logo.png" alt="" class="w-10 h-10 object-cover bg-white rounded-full">
                <p class="text-2xl font-medium tracking-tighter hidden lg:block">Sta. Marta Educational Center Inc.</p>
            </a>


            <div class="flex items-center ">
                
                <!-- Initial Items Menu -->
                <ul class="menu menu-horizontal px-1 font-medium hidden lg:flex text-white">
            
                <li><a href="../../aboutus.php">ABOUT US</a></li>
                <li><a href="../../programs.php">PROGRAMS</a></li>
                <li><a href="">NEWS & EVENTS</a></li>
                <li><a href="../">ADMISSIONS</a></li>
                <li><a href="../../portal/">PORTALS</a></li>
                <li><a href="../../auth/login.php">LOGIN</a></li>

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
                        <li><a href="../../aboutus.php">ABOUT US</a></li>
                        <li><a href="../../programs.php">PROGRAMS</a></li>
                        <li><a href="">NEWS & EVENTS</a></li>
                        <li><a href="../">ADMISSIONS</a></li>
                        <li><a href="../../portal/">PORTALS</a></li>
                        <li><a href="../../auth/login.php">LOGIN</a></li>

                        </ul>
                    </div>

                </div>

            </div>



        </div>









    </div>






  
    <div class="py-36 px-4 lg:px-12 bg-blue-800 "> 
            
            <div class="space-y-6  max-w-7xl mx-auto text-white">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-extrabold mb-1">School Admission Form</h2>

                    <p class="font-light mb-6">Enter your admission information below</p>

                    <a href="../" class=" text-sm bg-blue-500 hover:bg-blue-700 transition-colors py-2 px-4  text-white rounded-md inline-flex items-center gap-2" target="_blank">
                                <span>View Admission Requirements</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                        </a>
    
                </div>
               
            </div>

    </div>

    <div class="py-16  px-4 lg:px-12 border border-gray-100"> 
            

            <div class="max-w-7xl mx-auto ">
                <form action="" method="POST" class="space-y-6 ">
                <!-- Name -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">1. Personal Information</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <input name="first_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">First Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="middle_initial" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="last_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                        </div>
                    </div>
                </div>

                <!-- Birthdate, Gender and Year Level -->
                <div>
                  
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        
                        <div>
           
                            <div class="relative flex items-center">
                            <input name="birth_date" type="date" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" />
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Birth Date</p>
                          
                        </div>

                        <div>
                       
                            <div class="relative flex items-center">
                                <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                    <option value="" disabled selected>Select your gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Gender</p>
                           
                        </div>

                        <div>
                   
                            <div class="relative flex items-center">
                                <select name="year_level" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                    <option value="" disabled selected>Select year level</option>
                                    <option >Grade 1</option>
                                    <option >Grade 2</option>
                                    <option >Grade 3</option>
                                    <option >Grade 4</option>
                                    <option >Grade 5</option>
                                    <option >Grade 6</option>
                                </select>
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Year Level to Enroll On</p>
                         
                        </div>

                    </div>
                </div>

                <!-- Parent/Guardian Name -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">2. Parent/Guardian Name</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <input name="parent_first_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">First Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="parent_middle_initial" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="parent_last_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                        </div>
                    </div>
                </div>


                <!-- Address -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">3. Address</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <select id="region" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"></select>
                            <input type="hidden" name="region_text" id="region-text">
    
                            </div>
                            <label class="text-sm font-light mt-1 ml-1">Region</label>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                        
                            <select id="province" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"></select>
                            <input type="hidden" name="province_text" id="province-text">
    
                            </div>
                            <label class="text-sm font-light mt-1 ml-1">Province</label>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                        
                            <select id="city" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"></select>
                            <input type="hidden" name="city_text" id="city-text">

    
                            </div>
                            <label class="text-sm font-light mt-1 ml-1">City</label>
                        </div>



                        <div>
                            <div class="relative flex items-center">
                        
                            <select id="barangay" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"></select>
                            <input type="hidden" name="barangay_text" id="barangay-text">
    
                            </div>
                            <label class="text-sm font-light mt-1 ml-1">Barangay</label>
                        </div>

                                 
                        <div>
                          
                            <div class="relative flex items-center">
                            <input name="zip_code" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <label class="text-sm font-light mt-1 ml-1">Zip Code</label>
                          
                        </div>


                       
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">4. Contact Information</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <input name="phone" type="number" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Phone Number</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Email</p>
                        </div>

                        
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">5. Emergency Contact</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <input name="emergency_first_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">First Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="emergency_last_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="relationship" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Relationship</p>
                        </div>

                        
                    </div>
                </div>

                <div class="border-gray-100 border-b"></div>

                <!-- Submit Form -->
                <div class=" flex items-center justify-end">
                    <button type="submit" class=" py-3 px-16 text-sm rounded-md text-white font-medium tracking-wide bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 focus:ring-offset-blue-50 transition-colors group">Submit Form</button>
                </div>

                </form>
               
            </div>

    </div>


    
    <footer class="h-full pt-24 px-4 mx-auto max-w-7xl">


        <div class="pb-8 flex flex-col md:flex-row lg:flex-row items-start justify-between gap-16 ">

        
            <p class="text-xl font-black tracking-tighter">Sta. Marta Educational Center Inc.</p>
            

            <div class="flex flex-col sm:flex-row lg:flex-row gap-16 lg:gap-16 items-start">
                <div class="space-y-8 text-sm">
                        <p>Quick Links</p>

                        <div class="font-medium space-y-4 flex flex-col">
                            <a href="#" class="hover:text-blue-500 transition-colors group">About</a>
                            <a href="#" class="hover:text-blue-500 transition-colors group">Academic Programs</a>
                            <a href="#" class="hover:text-blue-500 transition-colors group">News & Events</a>
                        </div>
                </div>

                <div class="space-y-8 text-sm">
                        <p>Contact Us</p>

                        <div class="font-medium space-y-4 flex flex-col">
                            <a href="#" class="hover:text-blue-500 transition-colors group">#01 Dolmar Subdivision, Kalawaan, Pasig City </a>
                            <a href="#" class="hover:text-blue-500 transition-colors group">Phone: 8642-2591</a>
                            <a href="#" class="hover:text-blue-500 transition-colors group">Email: smecdolmarpasig@gmail.com | smec_dolmarpasig@yahoo.com</a>
                        </div>
                </div>

                <div class="space-y-8 text-sm">
                        <p>Connect</p>

                        <div class="font-medium space-y-4 flex flex-col">
                            <a href="#" class="hover:text-blue-500 transition-colors group">Facebook</a>
                            <a href="#" class="hover:text-blue-500 transition-colors group">Instagram</a>
                    
                        </div>
                </div>

            </div>

                

                
                </div>

                <div class="h-full py-8 container mx-auto text-gray-400 ">

                    <div class="text-xs flex flex-wrap items-center gap-8 ">
                        <p>© 2024 SMEC. All Rights Reserved.</p>
                        <a href="" class="hover:text-black transition-colors">Privacy</a>
                        <a href="" class="hover:text-black transition-colors">Cookies Policy</a>
                    </div>


                </div>


    </footer>




      
  

    
</body>
</html>



<script>
    document.addEventListener("scroll", function () {
    const navbar = document.getElementById("navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("bg-blue-800");
    } else {
        navbar.classList.remove("bg-blue-800");
    }
    });

</script>




<script>

            /**
         * __________________________________________________________________
         *
         * Phillipine Address Selector
         * __________________________________________________________________
         *
         * MIT License
         * 
         * Copyright (c) 2020 Wilfred V. Pine
         * 
         * Permission is hereby granted, free of charge, to any person obtaining a copy
         * of this software and associated documentation files (the "Software"), to deal
         * in the Software without restriction, including without limitation the rights
         * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
         * copies of the Software, and to permit persons to whom the Software is
         * furnished to do so, subject to the following conditions:
         *
         * The above copyright notice and this permission notice shall be included in
         * all copies or substantial portions of the Software.
         *
         * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
         * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
         * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
         * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
         * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
         * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
         * THE SOFTWARE.
         *
         * @package Phillipine Address Selector
         * @author Wilfred V. Pine <only.master.red@gmail.com>
         * @copyright Copyright 2020 (https://dev.confired.com)
         * @link https://github.com/redmalmon/philippine-address-selector
         * @license https://opensource.org/licenses/MIT MIT License
         */

        var my_handlers = {
            // fill province
            fill_provinces: function() {
                //selected region
                var region_code = $(this).val();

                // set selected text to input
                var region_text = $(this).find("option:selected").text();
                let region_input = $('#region-text');
                region_input.val(region_text);
                //clear province & city & barangay input
                $('#province-text').val('');
                $('#city-text').val('');
                $('#barangay-text').val('');

                //province
                let dropdown = $('#province');
                dropdown.empty();
                dropdown.append('<option selected="true" disabled>Choose State/Province</option>');
                dropdown.prop('selectedIndex', 0);

                //city
                let city = $('#city');
                city.empty();
                city.append('<option selected="true" disabled></option>');
                city.prop('selectedIndex', 0);

                //barangay
                let barangay = $('#barangay');
                barangay.empty();
                barangay.append('<option selected="true" disabled></option>');
                barangay.prop('selectedIndex', 0);

                // filter & fill
                var url = 'ph-json/province.json';
                $.getJSON(url, function(data) {
                    var result = data.filter(function(value) {
                        return value.region_code == region_code;
                    });

                    result.sort(function(a, b) {
                        return a.province_name.localeCompare(b.province_name);
                    });

                    $.each(result, function(key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.province_code).text(entry.province_name));
                    })

                });
            },
            // fill city
            fill_cities: function() {
                //selected province
                var province_code = $(this).val();

                // set selected text to input
                var province_text = $(this).find("option:selected").text();
                let province_input = $('#province-text');
                province_input.val(province_text);
                //clear city & barangay input
                $('#city-text').val('');
                $('#barangay-text').val('');

                //city
                let dropdown = $('#city');
                dropdown.empty();
                dropdown.append('<option selected="true" disabled>Choose city/municipality</option>');
                dropdown.prop('selectedIndex', 0);

                //barangay
                let barangay = $('#barangay');
                barangay.empty();
                barangay.append('<option selected="true" disabled></option>');
                barangay.prop('selectedIndex', 0);

                // filter & fill
                var url = 'ph-json/city.json';
                $.getJSON(url, function(data) {
                    var result = data.filter(function(value) {
                        return value.province_code == province_code;
                    });

                    result.sort(function(a, b) {
                        return a.city_name.localeCompare(b.city_name);
                    });

                    $.each(result, function(key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.city_code).text(entry.city_name));
                    })

                });
            },
            // fill barangay
            fill_barangays: function() {
                // selected barangay
                var city_code = $(this).val();

                // set selected text to input
                var city_text = $(this).find("option:selected").text();
                let city_input = $('#city-text');
                city_input.val(city_text);
                //clear barangay input
                $('#barangay-text').val('');

                // barangay
                let dropdown = $('#barangay');
                dropdown.empty();
                dropdown.append('<option selected="true" disabled>Choose barangay</option>');
                dropdown.prop('selectedIndex', 0);

                // filter & Fill
                var url = 'ph-json/barangay.json';
                $.getJSON(url, function(data) {
                    var result = data.filter(function(value) {
                        return value.city_code == city_code;
                    });

                    result.sort(function(a, b) {
                        return a.brgy_name.localeCompare(b.brgy_name);
                    });

                    $.each(result, function(key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.brgy_code).text(entry.brgy_name));
                    })

                });
            },

            onchange_barangay: function() {
                // set selected text to input
                var barangay_text = $(this).find("option:selected").text();
                let barangay_input = $('#barangay-text');
                barangay_input.val(barangay_text);
            },

        };


        $(function() {
            // events
            $('#region').on('change', my_handlers.fill_provinces);
            $('#province').on('change', my_handlers.fill_cities);
            $('#city').on('change', my_handlers.fill_barangays);
            $('#barangay').on('change', my_handlers.onchange_barangay);

            // load region
            let dropdown = $('#region');
            dropdown.empty();
            dropdown.append('<option selected="true" disabled>Choose Region</option>');
            dropdown.prop('selectedIndex', 0);
            const url = 'ph-json/region.json';
            // Populate dropdown with list of regions
            $.getJSON(url, function(data) {
                $.each(data, function(key, entry) {
                    dropdown.append($('<option></option>').attr('value', entry.region_code).text(entry.region_name));
                })
            });

        });
</script>