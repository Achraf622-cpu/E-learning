<?php
require '../conexions/connect.php'; // Ensure this file provides $conn
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../conexions/login.php");
    exit;
}

if (isset($_GET['make_admin'])) {
    $user_id_to_promote = intval($_GET['make_admin']);
    $stmt = $conn->prepare("UPDATE users SET id_role = (SELECT id FROM roles WHERE name = 'admin') WHERE id = ?");
    $stmt->execute([$user_id_to_promote]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['make_user'])) {
    $user_id_to_demote = intval($_GET['make_user']);
    $stmt = $conn->prepare("UPDATE users SET id_role = (SELECT id FROM roles WHERE name = 'user') WHERE id = ?");
    $stmt->execute([$user_id_to_demote]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['ban_user'])) {
    $user_id_to_ban = intval($_GET['ban_user']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id_to_ban]);
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
            <li><a href="../index.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Dashboard</a></li>
            <li><a href="manage.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Courses</a></li>
            <li><a href="" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Users</a></li>
        </ul>
        <div class="mt-6">
            <a href="../conexions/logout.php" class="block text-red-500 hover:text-red-700 transition duration-300">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 p-8 bg-gradient-to-br from-white via-green-100 to-green-200 shadow-lg">
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-green-600 mb-4">Manage Users</h1>
            <p class="text-lg text-gray-600">Promote users to admins, demote admins to users, or ban users from the platform</p>
        </div>

        <!-- User List -->
        <div class="grid grid-cols-1 gap-6">
            <?php
            $stmt = $conn->prepare("SELECT u.id, u.username, u.email, r.name AS role 
                                    FROM users u 
                                    JOIN roles r ON u.id_role = r.id");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                foreach ($result as $row) {
                    ?>
                    <div class="relative bg-white p-6 rounded-lg shadow-lg border border-green-300">
                        <h3 class="text-2xl font-bold text-green-600 mb-2"><?php echo htmlspecialchars($row['username']); ?></h3>
                        <p class="text-gray-700 mb-2">Email: <?php echo htmlspecialchars($row['email']); ?></p>
                        <p class="text-gray-600 mb-4">Role: <?php echo htmlspecialchars($row['role']); ?></p>

                        <div class="flex space-x-4">
                            <?php if ($row['role'] === 'user') { ?>
                                <a href="?make_admin=<?php echo $row['id']; ?>"
                                   class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded-md transition duration-300">
                                    Make Admin
                                </a>
                            <?php } elseif ($row['role'] === 'admin') { ?>
                                <a href="?make_user=<?php echo $row['id']; ?>"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-2 px-4 rounded-md transition duration-300">
                                    Make User
                                </a>
                            <?php } ?>
                            <a href="?ban_user=<?php echo $row['id']; ?>"
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
