<?php
// Start the session
session_start();
include '../config/db.php';

// Get user ID from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    // Insert Audit Logs 
    $sql = "INSERT INTO audit_logs (user_id, action, resource_type, created_at) VALUES (?, 'User Logged Out', 'Session', NOW())";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: loading.php?target=login.php');
exit();
?>
