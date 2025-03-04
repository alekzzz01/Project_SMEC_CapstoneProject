<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();

include '../../config/db.php';


$sql = "SELECT * FROM customization_table WHERE theme_id = 1";
$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$customization = $result->fetch_assoc();



// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     echo "<pre>";
//     print_r($_POST);
//     print_r($_FILES);
//     echo "</pre>";
//     exit;
// }


if (isset($_POST['schoolInfo'])) {
    $school_name = $_POST['school-name'];
    $brand_color = $_POST['brand-color'];

    // Directory for saving uploaded logo
    $targetDir = "theme/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Handle file upload
    $schoolLogo = null;
    if (isset($_FILES['school-logo']) && $_FILES['school-logo']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['school-logo']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            $sanitizedFileName = "schoolLogo_" . uniqid() . "." . $fileType;
            $targetFilePath = $targetDir . $sanitizedFileName;

            if (move_uploaded_file($_FILES['school-logo']['tmp_name'], $targetFilePath)) {
                $schoolLogo = $targetFilePath;
            } else {
                $_SESSION['theme_error'] = "Failed to upload the file.";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } else {
            $_SESSION['theme_error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            header('Location: ' . $_SERVER['PHP_SELF']);;
            exit;
        }
    }

    // Check if a record exists
    $stmt = $connection->prepare("SELECT COUNT(*) FROM customization_table");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Update existing record
        if ($schoolLogo) {
            $stmt = $connection->prepare("UPDATE customization_table SET school_name = ?, school_logo = ?, brand_color = ? WHERE theme_id = 1");
            $stmt->bind_param("sss", $school_name, $schoolLogo, $brand_color);
        } else {
            $stmt = $connection->prepare("UPDATE customization_table SET school_name = ?, brand_color = ? WHERE theme_id = 1");
            $stmt->bind_param("ss", $school_name, $brand_color);
        }
    } else {
        // Insert new record
        $stmt = $connection->prepare("INSERT INTO customization_table (school_name, school_logo, brand_color) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $school_name, $schoolLogo, $brand_color);
    }

    // Execute query
    if ($stmt->execute()) {
        $_SESSION['theme_success'] = "Theme customization has been saved successfully.";
    } else {
        $_SESSION['theme_error'] = "Failed to save theme customization.";
    }

    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}



if (isset($_POST['removeLogo'])) {
    $stmt = $connection->prepare("UPDATE customization_table SET school_logo = NULL WHERE theme_id = 1");
    if ($stmt->execute()) {
        $_SESSION['theme_success'] = "School logo has been removed successfully.";
    } else {
        $_SESSION['theme_error'] = "Failed to remove school logo.";
    }

    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Customization</title>



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

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">


        <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#fafbfc] h-full">


            <?php if (isset($_SESSION['theme_success'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7"><?= $_SESSION['theme_success']; ?></div>
                <?php unset($_SESSION['theme_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['theme_error'])): ?>
                <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7"><?= $_SESSION['theme_error']; ?></div>
                <?php unset($_SESSION['theme_error']); ?>
            <?php endif; ?>


            <div class="flex items-center justify-between">
                <h1 class="text-lg font-medium mb-1">Theme Customization</h1>

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a>Dashboard</a></li>
                        <li><a>Theme</a></li>
                    </ul>
                </div>
            </div>

            <div class="my-7 grid grid-cols-1 lg:grid-cols-2 gap-4 ">


                <form method="POST" enctype="multipart/form-data" class="bg-white rounded-md border border-gray-200 p-5 space-y-4">


                    <p class="font-medium">School Information</p>


                    <div>

                        <label class="text-sm mb-2 block text-base-content/70">School Name</label>
                        <input
                            name="school-name"
                            type="text"
                            class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"
                            value="<?php echo isset($customization['school_name']) ? htmlspecialchars($customization['school_name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            placeholder="Enter School Name" />
                    </div>


                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">School Logo</label>
                        <input name="school-logo" type="file" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                    </div>


                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Brand Color</label>

                        <!-- <input name="school-logo" type="color" class="h-12 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" id="b" placeholder="# Hex code" /> -->
                        <input
                            name="brand-color"
                            type="text"
                            class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600"
                            id="b"
                            placeholder="# Hex code"
                            value="<?php echo isset($customization['brand_color']) ? htmlspecialchars($customization['brand_color'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
                    </div>


                    <div
                        id="a"
                        class="w-[80px] h-[60px] rounded-md"
                        style="background-color: <?php echo isset($customization['brand_color']) ? htmlspecialchars($customization['brand_color'], ENT_QUOTES, 'UTF-8') : '#ffffff'; ?>;">
                    </div>


                    <div>
                        <button type="submit" name="schoolInfo" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Save Changes</button>


                    </div>



                </form>




                <div class="bg-white rounded-md border border-gray-200 p-5 space-y-4">

                    <div>

                        <div>
                            <?php
                            // Check if there is a banner image
                            if (isset($customization['school_logo']) && !empty($customization['school_logo'])) {
                                echo '<p class="text-lg font-bold mb-6">Current School logo</p>';
                                echo '<img src="' . $customization['school_logo'] . '" alt="Event Banner" class="w-[128px] h-[128px] object-cover rounded-md mb-6 p-2 border border-gray-100">';
                            } else {
                                // If there is no banner, display a message
                                echo '<p class="mb-6">No school logo available. Upload one to display it here.</p>';
                            }
                            ?>

                            <form action="" method="POST">

                                <div>
                                    <button type="submit" name="removeLogo" class="btn btn-error text-white">Remove logo</button>
                                </div>

                            </form>


                        </div>

                    </div>


                </div>



            </div>


        </div>



    </div>

</body>

</html>


<script>
    document.getElementById("b").addEventListener("change", function(e) {
        document.getElementById("a").style.backgroundColor = e.target.value;
    })
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