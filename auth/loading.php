<?php
session_start();

$target = isset($_GET['target']) ? $_GET['target'] : 'login.php';
$teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '';

// If teacher_id exists, append it to the target URL
if (!empty($teacher_id)) {
    // Check if target already has parameters
    if (strpos($target, '?') !== false) {
        $target .= '&teacher_id=' . $teacher_id;
    } else {
        $target .= '?teacher_id=' . $teacher_id;
    }
}
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
            border: 5px solid #3b82f6;
            border-top: 5px solid #d4d4d8;
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
