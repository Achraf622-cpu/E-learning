<?php
require '../conexions/connect.php';
require_once '../conexions/admin.php'; 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../conexions/login.php");
    exit;
}
$conn = new Connection();
$admin = new Admin($_SESSION['user_id'], $_SESSION['username'], $conn);
if (isset($_GET['delete_course'])) { 
    $course_id = intval($_GET['delete_course']); 
    $admin->deleteCourse($course_id); // Use Admin class method for deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
// Filtering and sorting logic
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'recent';
// Fetch courses using Admin class method
$courses = $admin->getCourses($filter_date, $sort_order);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #ffffff, #d4fc79); /* Correct Minty Green-to-White Gradient */
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

        .text-mint {
            color: #38b48b;
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
    <aside class="w-1/4 bg-white p-6 shadow-md border-r border-green-300">
        <h2 class="text-3xl font-extrabold text-green-600 mb-6">Admin Panel</h2>
        <ul class="space-y-6">
        <li><a href="manage.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Courses</a></li>
        <li><a href="../home.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Home</a></li>
        <li><a href="adminpro.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Dashboard</a></li>
        <li><a href="admin.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Users</a></li>
        <li><a href="pending.php" class="block text-gray-800 hover:text-green-500 transition duration-300">Manage Pendings</a></li>
        </ul>
        <div class="mt-6">
            <a href="../conexions/logout.php" class="block text-red-500 hover:text-red-700 transition duration-300">Logout</a>
        </div>
    </aside>
    <!-- Main Content -->
    <main class="w-3/4 p-8 bg-gradient-to-br from-white via-green-100 to-green-200 shadow-lg">
        <div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-green-600 mb-4">Manage Courses</h1>
            <p class="text-lg text-gray-700">View, filter, and manage courses</p>
        </div>
        <!-- Filters -->
        <form method="GET" class="mb-8 text-center flex items-center justify-center gap-4">
            <div>
                <label for="filter_date" class="text-gray-700 mr-4">Filter by Date:</label>
                <input type="date" id="filter_date" name="filter_date" value="<?php echo htmlspecialchars($filter_date); ?>"
                       class="input-mint p-2 rounded">
            </div>
            <div>
                <label for="sort_order" class="text-gray-700 mr-4">Sort:</label>
                <select id="sort_order" name="sort_order" class="input-mint p-2 rounded">
                    <option value="recent" <?php echo $sort_order === 'recent' ? 'selected' : ''; ?>>Recent</option>
                    <option value="oldest" <?php echo $sort_order === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
                </select>
            </div>
            <div>
                <button type="submit" class="button-mint text-white py-2 px-4 rounded-md">
                    Apply
                </button>
            </div>
        </form>
        <!-- Course List -->
        <div class="grid grid-cols-3 gap-6">
            <?php
            // Assume $courses is populated with courses data
            if ($courses) {
                foreach ($courses as $row) {
                    ?>
                    <div class="card p-6 rounded-lg">
                        <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Course Thumbnail" class="w-full h-40 object-cover rounded-md mb-4">
                        <h3 class="text-2xl font-bold text-green-600 mb-2"><?php echo htmlspecialchars($row['titre']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($row['para'], 0, 100)) . '...'; ?></p>
                        <p class="text-gray-500 text-sm mb-4">By: <?php echo htmlspecialchars($row['author']); ?></p>
                        <p class="text-gray-500 text-sm mb-4">Published on: <?php echo htmlspecialchars($row['date']); ?></p>
                        <div class="flex justify-end">
                            <a href="?delete_course=<?php echo $row['id']; ?>" class="button-mint text-white py-2 px-4 rounded-md"
                               onclick="return confirm('Are you sure you want to delete this course?');">
                                Delete Course
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-gray-700 col-span-3'>No courses found.</p>";
            }
            ?>
        </div>
    </main>

</div>

</body>
</html>



