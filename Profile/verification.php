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

// Debugging session data (optional, can be removed in production)
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Role: " . $_SESSION['role'] . "<br>";

// Redirect based on role
switch ($_SESSION['role']) {
    case 'admin':
        echo "Redirecting to admin profile...";
        header("Location: ../admin/admin.php");
        exit;

    case 'enseignant':
        echo "Redirecting to teacher profile...";
        header("Location: profile.php");
        exit;

    case 'student':
        echo "Redirecting to student profile...";
        header("Location: studentprofile.php");
        exit;

    default:
        echo "Invalid role detected. Redirecting to login...";
        header("Location: ../conexions/login.php");
        exit;
}
?>
