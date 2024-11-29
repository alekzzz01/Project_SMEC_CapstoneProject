<?php
session_start();

require_once('./config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $middle_initial = mysqli_real_escape_string($connection, $_POST['middle_initial']);
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $birth_date = mysqli_real_escape_string($connection, $_POST['birth_date']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $year_level = mysqli_real_escape_string($connection, $_POST['yearlevel']);
    $parent_first_name = mysqli_real_escape_string($connection, $_POST['parent_first_name']);
    $parent_last_name = mysqli_real_escape_string($connection, $_POST['parent_last_name']);
    $region = mysqli_real_escape_string($connection, $_POST['region']);
    $province = mysqli_real_escape_string($connection, $_POST['province']);
    $city = mysqli_real_escape_string($connection, $_POST['city']);
    $barangay = mysqli_real_escape_string($connection, $_POST['barangay']);
    $zip_code = mysqli_real_escape_string($connection, $_POST['zip_code']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $emergency_first_name = mysqli_real_escape_string($connection, $_POST['emergency_first_name']);
    $emergency_last_name = mysqli_real_escape_string($connection, $_POST['emergency_last_name']);
    $relationship = mysqli_real_escape_string($connection, $_POST['relationship']);

    $query = "INSERT INTO admission_form
              (first_name, middle_initial, last_name, birth_date, gender, year_level, 
               parent_first_name, parent_last_name, region, province, city, barangay, zip_code, 
               phone, email, emergency_first_name, emergency_last_name, relationship, created_at) 
              VALUES 
              ('$first_name', '$middle_initial', '$last_name', '$birth_date', '$gender', '$year_level', 
               '$parent_first_name', '$parent_last_name', '$region', '$province', '$city', '$barangay', 
               '$zip_code', '$phone', '$email', '$emergency_first_name', '$emergency_last_name', '$relationship', NOW())";

    if ($connection->query($query) === TRUE) {
        $_SESSION['message'] = "Your admission form has been successfully submitted.";

        header("Location: ./index.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $connection->error;
    }

    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission</title>

    <link rel="stylesheet" href="./assets/css/styles.css">
     
     <script src="../assets/js/script.js"></script>
 
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


    <?php include './publicnavbar.php' ?>

  
    <div class="py-16  px-4 lg:px-12 border-b border-gray-100 "> 
            
            <div class="space-y-6 container mx-auto ">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-extrabold mb-1">School Admission Form</h2>

                    <p class="text-gray-400 font-light">Enter your admission information below</p>
                </div>
               
            </div>

    </div>

    <div class="py-16  px-4 lg:px-12 "> 
            
            <div class="space-y-6 container mx-auto ">
                <form action="" method="POST" class="space-y-6">
                 
                
                <!-- Name -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">Name</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">First Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                        </div>
                    </div>
                </div>

                <!-- Birthdate, Gender and Year Level -->
                <div>
                  
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        
                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Birth Date</label>
                            <div class="relative flex items-center">
                            <input name="email" type="date" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter your email" />
                        
                            </div>
                          
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Gender</label>
                            <div class="relative flex items-center">
                                <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                    <option value="" disabled selected>Select your gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                           
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Year Level To Enroll On</label>
                            <div class="relative flex items-center">
                                <select name="yearlevel" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                    <option value="" disabled selected>Select year level</option>
                                    <option >Grade 1</option>
                                    <option >Grade 2</option>
                                    <option >Grade 3</option>
                                    <option >Grade 4</option>
                                    <option >Grade 5</option>
                                    <option >Grade 6</option>
                                </select>
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
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">First Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Middle Initial</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"  />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">Last Name</p>
                        </div>
                    </div>
                </div>


                <!-- Address -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">Address</label>
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
                            <input name="ZipCode" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <label class="text-sm font-light mt-1 ml-1">Zip Code</label>
                          
                        </div>


                       
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <label class="text-gray-800 text-sm font-medium mb-6 block">Contact Information</label>
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
                    <label class="text-gray-800 text-sm font-medium mb-6 block">Emergency Contact</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                        <div>
                            <div class="relative flex items-center">
                            <input name="firstName" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
                            </div>
                            <p class="text-sm font-light mt-1 ml-1">First Name</p>
                        </div>

                        <div>
                            <div class="relative flex items-center">
                            <input name="lastName" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        
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


    
    <?php include './footer.php' ?>
      
  

    
</body>
</html>



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