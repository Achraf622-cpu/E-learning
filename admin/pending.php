<?php
require '../conexions/connect.php';
require '../conexions/admin.php';

session_start();

$conn = new Connection();
$admin = new Admin($_SESSION['user_id'], $_SESSION['username'], $conn);

// Handle enseignant approval actions
if (isset($_GET['approve'])) {
    $request_id = intval($_GET['approve']);
    $admin->validateTeacherAccount($request_id); // Approve the enseignant request
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['reject'])) {
    $request_id = intval($_GET['reject']);
    $admin->rejectTeacherAccount($request_id); // Reject the enseignant request
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
        <li><a href="pending.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Pendings</a></li>
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
            <h1 class="text-5xl font-extrabold text-green-600 mb-4">Manage Enseignant Approvals</h1>
            <p class="text-lg text-gray-600">Approve or reject enseignant requests</p>
        </div>

        <!-- Enseignant Approval Requests -->
        <div class="grid grid-cols-1 gap-6">
            <?php
            // Fetch all pending approval requests
            $requests = $admin->getPendingApprovalRequests();

            if ($requests) {
                foreach ($requests as $request) {
                    ?>
                    <div class="relative bg-white p-6 rounded-lg shadow-lg border border-green-300">
                        <h3 class="text-2xl font-bold text-green-600 mb-2"><?php echo htmlspecialchars($request['username']); ?></h3>
                        <p class="text-gray-700 mb-2">Email: <?php echo htmlspecialchars($request['email']); ?></p>
                        <p class="text-gray-600 mb-4">Request Status: <?php echo htmlspecialchars($request['status']); ?></p>

                        <div class="flex space-x-4 mt-4">
                            <!-- Approve Button -->
                            <a href="?approve=<?php echo $request['id']; ?>"
                               class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded-md transition duration-300">
                                Approve
                            </a>

                            <!-- Reject Button -->
                            <a href="?reject=<?php echo $request['id']; ?>"
                               class="bg-red-500 hover:bg-red-600 text-white text-sm py-2 px-4 rounded-md transition duration-300"
                               onclick="return confirm('Are you sure you want to reject this request?');">
                                Reject
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-gray-600'>No pending requests found.</p>";
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>
