<?php 


if (isset($_SESSION['last_Activity'])) {
    if (time() - $_SESSION['last_Activity'] > 1800) { // 30 minutes
        session_unset();
        session_destroy();
        echo "<script>alert('Session Expired! Please Login Again')</script>";
        header('Location: ../../index.php');
        exit();
    }
}

$_SESSION['last_Activity'] = time();



?>