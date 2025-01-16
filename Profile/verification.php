<?php
require '../conexions/connect.php';
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo "Session data is missing. Redirecting to login...";
    header("Location: ../conexions/login.php");
    exit;
}

// Debugging session data
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Role: " . $_SESSION['role'] . "<br>";

// Redirect based on role
if ($_SESSION['role'] == 'admin') {
    echo "Redirecting to admin profile...";
    header("Location: ../admin/admin.php");
    exit;
} else {
    echo "Redirecting to user profile...";
    header("Location: profile.php");
    exit;
}
?>
