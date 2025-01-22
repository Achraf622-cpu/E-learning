<?php
session_start();
require '../conexions/connect.php'; 
require '../conexions/admin.php';
$conn = new Connection();
$admin = new Admin($_SESSION['user_id'], $_SESSION['username'], $conn);

$totalStudents = $admin->getUserCountByRole('student');
$totalCourses = $admin->getTotalCourses();
$totalTeachers = $admin->getUserCountByRole('enseignant');

if (isset($_GET['ban_user'])) {
    $admin->suspendUser($_GET['ban_user']);
    header("Location: manage_statistics.php"); // Refresh the page after banning user
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Statistics</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #d4fc79, #96e6a1); /* Minty Green-to-White Gradient */
        }

        .card {
            background: linear-gradient(145deg, #ffffff, #e0f7df);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .button-mint {
            background-color: #38b48b;
            transition: background-color 0.3s, transform 0.3s;
        }

        .button-mint:hover {
            background-color: #2a926e;
            transform: scale(1.05);
        }

        .input-mint {
            border: 2px solid #38b48b;
            color: #333;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-mint:focus {
            border-color: #2a926e;
            box-shadow: 0 0 4px #38b48b;
        }
    </style>
</head>
<body class="text-gray-900">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-1/4 bg-white p-6 shadow-md">
        <h2 class="text-3xl font-extrabold text-mint mb-6">Admin Panel</h2>
        <ul class="space-y-6">
            <li><a href="adminpro.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Dashboard</a></li>
            <li><a href="manage.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Courses</a></li>
            <li><a href="admin.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Users</a></li>
            <li><a href="../home.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Home</a></li>
            <li><a href="pending.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Pendings</a></li>
        </ul>
        <div class="mt-6">
            <a href="../conexions/logout.php" class="block text-red-500 hover:text-red-700 transition duration-300">Logout</a>
        </div>
    </aside>
    <!-- Main Content -->
    <main class="w-3/4 p-8">
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-mint mb-4">Admin Dashboard - Statistics</h1>
            <p class="text-lg text-gray-700">View statistics for the app</p>
        </div>
        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Total Students -->
            <div class="card p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-mint mb-2">Total Students</h3>
                <p class="text-xl text-gray-700"><?php echo $totalStudents; ?></p>
            </div>
            <!-- Total Courses -->
            <div class="card p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-mint mb-2">Total Courses</h3>
                <p class="text-xl text-gray-700"><?php echo $totalCourses; ?></p>
            </div>
            <!-- Total Teachers -->
            <div class="card p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-mint mb-2">Total Teachers</h3>
                <p class="text-xl text-gray-700"><?php echo $totalTeachers; ?></p>
            </div>
        </div>
    </main>
</div>
</body>
</html>
