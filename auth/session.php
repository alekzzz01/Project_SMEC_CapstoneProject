<?php 


if (isset($_SESSION['last_Activity'])) {
    if (time() - $_SESSION['last_Activity'] > 20) { // 30 minutes
        session_unset();
        session_destroy();
        echo "<script>
                alert('Session Expired! Please Login Again');
                window.location.href = '../../index.php';
              </script>";
        exit(); // Stop further execution
    }
}
$_SESSION['last_Activity'] = time();



?>