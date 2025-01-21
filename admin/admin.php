<?php
require '../conexions/connect.php'; 
require '../conexions/admin.php'; 

session_start();

// Verify the admin role
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../conexions/login.php");
//     exit;
// }
$conn = new Connection();
// Initialize the Admin class with the provided PDO connection
$admin = new Admin($_SESSION['user_id'], $_SESSION['username'], $conn);

// Handle actions using the new Admin class
if (isset($_GET['promote'])) {
    $user_id = intval($_GET['promote']);
    $admin->activateUser($user_id); // Activate user (could be promoting to admin if needed)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['demote'])) {
    $user_id = intval($_GET['demote']);
    $admin->suspendUser($user_id); // Suspend user (could be demoting to user if needed)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['ban'])) {
    $user_id = intval($_GET['ban']);
    $admin->deleteUser($user_id); // Delete user (assuming banning means deleting)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-400 via-white to-green-300 text-gray-800">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-1/4 bg-white p-6 border-r border-green-300 shadow-lg">
        <h2 class="text-3xl font-extrabold text-green-600 mb-6">Admin Panel</h2>
        <ul class="space-y-6">
            <li><a href="admin.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Users</a></li>
            <li><a href="../home.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Home</a></li>
            <li><a href="adminpro.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Dashboard</a></li>
            <li><a href="manage.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Courses</a></li>
        </ul>
        <div class="mt-6">
            <a href="../conexions/logout.php" class="block text-red-500 hover:text-red-700 transition duration-300">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 p-8 bg-gradient-to-br from-white via-green-100 to-green-200 shadow-lg">
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-green-600 mb-4">Manage Users</h1>
            <p class="text-lg text-gray-600">Promote, demote, or ban users with ease</p>
        </div>

        <!-- User List -->
        <div class="grid grid-cols-1 gap-6">
            <?php
            // Fetch all users using the new class
            $users = $admin->getAllUsers();

            if ($users) {
                foreach ($users as $user) {
                    ?>
                    <div class="relative bg-white p-6 rounded-lg shadow-lg border border-green-300">
                        <h3 class="text-2xl font-bold text-green-600 mb-2"><?php echo htmlspecialchars($user['username']); ?></h3>
                        <p class="text-gray-700 mb-2">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="text-gray-600 mb-4">Role: <?php echo htmlspecialchars($user['role']); ?></p>

                        <div class="flex space-x-4 mt-4">
                            <!-- Promote Button: Only visible if the user is not already an admin -->
                            <?php if ($user['role'] === 'user') { ?>
                                <a href="?promote=<?php echo $user['id']; ?>"
                                   class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded-md transition duration-300">
                                    Promote to Admin
                                </a>
                            <?php } ?>

                            <!-- Demote Button: Only visible if the user is an admin -->
                            <?php if ($user['role'] === 'admin') { ?>
                                <a href="?demote=<?php echo $user['id']; ?>"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-2 px-4 rounded-md transition duration-300">
                                    Demote to User
                                </a>
                            <?php } ?>

                            <!-- Ban Button: Visible for all roles, allows banning any user -->
                            <a href="?ban=<?php echo $user['id']; ?>"
                               class="bg-red-500 hover:bg-red-600 text-white text-sm py-2 px-4 rounded-md transition duration-300"
                               onclick="return confirm('Are you sure you want to ban this user?');">
                                Ban User
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-gray-600'>No users found.</p>";
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>
