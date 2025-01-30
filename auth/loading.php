<?php
session_start();

// // Get the target page from the URL
if (!isset($_GET['target'])) {
    header("Location: login.php"); // Default page if no target is specified
    exit();
}

$target = $_GET['target']; // Get the target page from URL


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "<?php echo $target; ?>"; // Redirect after 2 seconds
        }, 2000);
    </script>
</head>
<body>
    <div class="loader"></div>
</body>
</html>
