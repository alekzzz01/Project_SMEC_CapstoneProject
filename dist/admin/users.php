<?php
session_start();
include '../../config/db.php';

// Add user
if (isset($_POST['createUser'])) {
    // Collect form data
    $role = $_POST['role'];
   
    $student_number = $_POST['student_number'];  // Only for students
    $dob = $_POST['dob'];                        // Only for students
    $gender = $_POST['gender'];                  // Only for students
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $first_name = $_POST['first_name'];
    $formatted_fname = ucwords(strtolower($first_name));
    $last_name = $_POST['last_name'];
    $formatted_lname = ucfirst(strtolower($last_name));

    $fullname = $first_name . ' ' . $last_name;
    $formatted_fullname = ucwords(strtolower($fullname)); 

    // Step 1: Check if the email already exists in the database
    $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    // Clear the result to allow for the next query to run
    $stmt->free_result();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = "Email is already in use. Please choose another one.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Step 2: Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Step 3: Insert into the users table
        $stmt = $connection->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $formatted_fullname, $email, $hashedPassword, $role);

        // Execute the statement for inserting the user
        if ($stmt->execute()) {
            // Get the inserted user ID
            $userId = $stmt->insert_id;

            // Clear the result and close the statement after the insert
            $stmt->free_result();
            $stmt->close();

            // Step 4: Handle role-specific data insertion
            if ($role == 'student') {
                // Insert student data into students table
                $stmt = $connection->prepare("UPDATE students SET user_id = ? WHERE student_number = ?");
                $stmt->bind_param("is", $userId, $student_number); // Fixed to use $userId

                // Execute the student insertion
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Student user added successfully!";
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $_SESSION['error'] = "Error inserting student data.";
                }
            } elseif ($role == 'teacher') {
                // Insert teacher data into teachers table
                $stmt = $connection->prepare("INSERT INTO teachers (user_id, first_name, last_name, date_of_birth, gender, contact_number) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $userId, $formatted_fname, $formatted_lname, $dob, $gender, $contact_number);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Teacher user added successfully!";
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $_SESSION['error'] = "Error inserting teacher data.";
                }
            } elseif ($role == 'admin') {
                // Handle admin-specific data if needed
                $_SESSION['message'] = "Admin user added successfully!";
            } else {
                $_SESSION['error'] = "Invalid role.";
            }
        } else {
            $_SESSION['error'] = "Error inserting user data.";
        }
    }
}

// Query for available student numbers that don't have a user_id
$sql = "SELECT student_number FROM students WHERE user_id IS NULL";
$result = $connection->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $connection->error);
}

// Generate options for student numbers
$options = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . htmlspecialchars($row['student_number']) . '">' . htmlspecialchars($row['student_number']) . '</option>';
    }
} else {
    $options = '<option value="" disabled>No available student numbers</option>';
}

// Close the database connection
$connection->close();
?>





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


    <html data-theme="light">

    </html>

</head>

<body class="flex min-h-screen ">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">

        <!-- Navbar -->

        <?php include('./components/navbar.php'); ?>


        <div class="p-6 bg-[#fafbfc] h-full">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7"><?= $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="flex items-center justify-between">


                <h1 class="text-lg font-medium mb-1">User management</h1>

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a>Dashboard</a></li>
                        <li><a>Users</a></li>
                    </ul>
                </div>


            </div>




            <div class="rounded-md mt-7 bg-white border border-gray-200">
                <?php include('./tables/userTable.php'); ?>
            </div>



        </div>

    </div>

    <!-- Modals -->

    <!-- Add user -->
    <dialog id="add_user" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add new user</h3>

            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <form action="" class="py-4 grid grid-cols-2 gap-6" method="POST">

                <div class="col-span-2">
                    <label class="text-gray-800 text-sm mb-2 block">Role</label>
                    <div class="relative flex items-center">
                        <select name="role" id="role" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" onchange="toggleStudentNumberField()">
                            <option value="" disabled selected>Select role</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>


                <div id="student_number_field" class="hidden col-span-2">
                    <label class="text-gray-800 text-sm mb-2 block">Student Number</label>
                    <div class="relative flex items-center">
                        <select name="student_number" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                            <option value="" disabled selected>Select Student Number</option>
                            <?php echo $options; ?>
                        </select>
                    </div>
                </div>


                <div id="first_name_field">
                    <label class="text-gray-800 text-sm mb-2 block">First Name</label>
                    <div class="relative flex items-center">
                        <input name="first_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter First Name" />

                    </div>
                </div>

                <div id="last_name_field">
                    <label class="text-gray-800 text-sm mb-2 block">Last Name</label>
                    <div class="relative flex items-center">
                        <input name="last_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Last Name" />

                    </div>
                </div>



                <div id="dob_field">
                    <label class="text-gray-800 text-sm mb-2 block">Date of Birth</label>
                    <div class="relative flex items-center">
                        <input name="dob" type="date" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter email" />

                    </div>
                </div>

                <div id="gender_field">
                    <label class="text-gray-800 text-sm mb-2 block">Gender</label>
                    <div class="relative flex items-center">
                        <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                            <option value="" disabled selected>Select your gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>


                <div class="col-span-2" id="contact_field">
                    <label class="text-gray-800 text-sm mb-2 block">Contact Number</label>
                    <div class="relative flex items-center">
                        <input name="contact_number" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Contact Number" />

                    </div>
                </div>


                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Email</label>
                    <div class="relative flex items-center">
                        <input name="email" type="email" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter email" />

                    </div>
                </div>



                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Password</label>
                    <div class="relative flex items-center mb-2">
                        <input id="edit_password" name="password" type="password" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                        <button type="button" onclick="togglePassword('edit_password', 'edit_togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                            <i id="edit_togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                        </button>
                    </div>

                    <button type="button" onclick="generateRandomPassword()" class="flex items-center gap-1 font-medium text-sm text-white border-2 border-blue-600 hover:border-blue-700 bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-1">
                        Generate password
                    </button>
                </div>



                <div class="modal-action col-span-2">

                    <button type="submit" name="createUser" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Add User</button>

                </div>


            </form>

        </div>


    </dialog>


    <!-- Delete user -->

    <dialog id="my_modal_7" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Delete user?</h3>
            <p class="py-4">Are you sure you want to delete this user?</p>

            <div class="modal-action">
                <form method="dialog">

                    <button class="btn">Close</button>
                    <button class="btn bg-red-500 hover:bg-red-700 text-white border border-red-500 hover:border-red-700">Confirm</button>
                </form>
            </div>
        </div>
    </dialog>




</body>

</html>


<script>
    function generateRandomPassword() {
        const length = 16; // Adjust the length of the password
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }
        document.querySelector('[name="password"]').value = password;
    }
</script>
<script>
    // JavaScript function to hide/show fields and manage 'required' validation based on the selected role
    function toggleStudentNumberField() {
        var role = document.getElementById('role').value;
        var studentNumberField = document.getElementById('student_number_field');
        var first_name_field = document.getElementById('first_name_field');
        var last_name_field = document.getElementById('last_name_field');
        var dob_field = document.getElementById('dob_field');
        var gender_field = document.getElementById('gender_field');
        var contact_field = document.getElementById('contact_field');

        var firstNameInput = document.getElementsByName('first_name')[0];
        var lastNameInput = document.getElementsByName('last_name')[0];
        var dobInput = document.getElementsByName('dob')[0];
        var genderInput = document.getElementsByName('gender')[0];
        var contactInput = document.getElementsByName('contact_number')[0];
        var studentNumberInput = document.getElementsByName('student_number')[0];

        // Show/hide the input fields based on the selected role
        if (role === 'student') {
            // For student, show student number and hide other fields
            studentNumberField.classList.remove('hidden');
            first_name_field.classList.add('hidden');
            last_name_field.classList.add('hidden');
            dob_field.classList.add('hidden');
            gender_field.classList.add('hidden');
            contact_field.classList.add('hidden');

            // Remove required validation from non-student fields
            firstNameInput.removeAttribute('required');
            lastNameInput.removeAttribute('required');
            dobInput.removeAttribute('required');
            genderInput.removeAttribute('required');
            contactInput.removeAttribute('required');

            // Ensure student number is required
            studentNumberInput.setAttribute('required', 'required');
        } else {
            // For teacher or admin, show other fields and hide student number
            studentNumberField.classList.add('hidden');
            first_name_field.classList.remove('hidden');
            last_name_field.classList.remove('hidden');
            dob_field.classList.remove('hidden');
            gender_field.classList.remove('hidden');
            contact_field.classList.remove('hidden');

            // Add required validation for non-student fields
            firstNameInput.setAttribute('required', 'required');
            lastNameInput.setAttribute('required', 'required');
            dobInput.setAttribute('required', 'required');
            genderInput.setAttribute('required', 'required');
            contactInput.setAttribute('required', 'required');

            // Remove required from student number
            studentNumberInput.removeAttribute('required');
        }
    }

    // Initialize the form with the correct visibility state on page load
    window.onload = function() {
        toggleStudentNumberField();
    };
</script>

<script>

$(document).ready(function() {
  $('#toggleSidebar').on('click', function() {
      $('#sidebar').toggleClass('-translate-x-full');
  });

   $('#closeSidebar').on('click', function() {
      $('#sidebar').addClass('-translate-x-full');
  });


  
});

</script>