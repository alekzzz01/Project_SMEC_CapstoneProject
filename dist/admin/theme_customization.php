<?php
session_start();

include '../../config/db.php';


$sql = "SELECT * FROM customization_table WHERE theme_id = 1";
$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$customization = $result->fetch_assoc();



if (isset($_POST['submit'])) {
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
    <title>Document</title>

    
      
    <link rel="stylesheet" href="../../assets/css/styles.css">
     
    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light"></html>

    
</head>
<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">

        
        <?php include('./components/navbar.php'); ?>

        <div class="p-7 bg-gray-50 h-full">
                <h1 class="text-2xl font-bold">Theme Customization</h1>

                <form class="p-6 bg-white rounded-md my-7 grid grid-cols-1 lg:grid-cols-2 gap-16" method="POST" enctype="multipart/form-data">


                    <div class="max-w-3xl  space-y-8 ">

            
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <p class="text-lg font-bold">School name</p>
                                <p class="text-gray-400">Enter your school name.</p>
                            </div>

                        
                            <div class="relative flex items-center">
                                <input name="school-name" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo $customization['school_name'] ?>" />
                            </div>    
                            
                        </div>

                        
                        <div class="border-b border-gray-100"></div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <p class="text-lg font-bold">School logo</p>
                                <p class=" text-gray-400">Choose a file to upload for the school logo.</p>
                            </div>


                            <div class="relative flex items-center">
                                <input name="school-logo" type="file"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                            </div>             
                        
                        </div>

                        <div class="border-b border-gray-100"></div>


                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <p class="text-lg font-bold">Brand color</p>
                                <p class=" text-gray-400">Select or customize your brand color.</p>
                            </div>


                            <div class="relative flex items-center gap-6">
                                <div id="a" class="w-[80px] h-[60px] rounded-md" style="background-color: <?php echo $customization['brand_color']; ?>;"></div>
                                <!-- <input name="school-logo" type="color"  class="h-12 text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" id="b" placeholder="# Hex code" /> -->
                                <input name="brand-color" type="text"  class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" id="b" placeholder="# Hex code" value="<?php echo $customization['brand_color'] ?>"  />
                            </div>             

                        </div>

                        <div>
                        <button type="submit" name="submit" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Save Changes</button>
                        </div>


                    </div>

           
                    <!-- existing logo -->

                    <div>

                        <div>
                            <?php
                                // Check if there is a banner image
                                if ($customization['school_logo']) {
                                    echo '<p class="text-lg font-bold mb-6">Current School logo</p>';
                                    echo '<img src="'. $customization['school_logo'] . '" alt="Event Banner" class="w-[128px] h-[128px] object-cover rounded-md mb-6 p-2 border border-gray-100">';
                                } else {
                                    // If there is no banner, display a message
                                echo '<p class="mb-6">No logo available for this school.</p>';
                                    }
                            ?>

                            <form action="" method="POST">

                                <div>
                                <button type="submit" name="removeLogo" class="btn bg-red-500 hover:bg-red-700 text-white border border-red-500 hover:border-red-700">Remove logo</button>
                                </div>

                            </form>


                        </div>

                    </div>


                </form>

                
                <?php if (isset($_SESSION['theme_success'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7"   ><?= $_SESSION['theme_success']; ?></div>
                <?php unset($_SESSION['theme_success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['theme_error'])): ?>
                        <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7" ><?= $_SESSION['theme_error']; ?></div>
                        <?php unset($_SESSION['theme_error']); ?>
                <?php endif; ?>
        </div>



    </div>
    
</body>
</html>


<script>

document.getElementById("b").addEventListener("change",function(e){
  document.getElementById("a").style.backgroundColor = e.target.value;
})
</script>