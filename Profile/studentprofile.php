<?php  
require '../conexions/connect.php';
require 'course.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$conn = new Connection();
$student_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
// Handle course unsubscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsubscribe_course_id'])) {
    try {
        $course_id = $_POST['unsubscribe_course_id'];

        // Update the `student_courses` table to mark the course as unsubscribed
        $stmt = $conn->prepare("UPDATE student_courses SET active = 0 WHERE student_id = ? AND course_id = ?");
        $stmt->execute([$student_id, $course_id]);

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Error!', 'Failed to unsubscribe from the course.', 'error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-400 via-white to-blue-200 text-gray-800">
<div class="flex min-h-screen">
    <aside class="w-1/4 bg-gradient-to-b from-blue-500 to-blue-600 p-6 border-r border-blue-300 text-white">
        <h2 class="text-3xl font-extrabold mb-6">Student Menu</h2>
        <ul class="space-y-6">
            <li><a href="#" class="block hover:text-blue-200 transition duration-300">Profile</a></li>
            <li><a href="../home.php" class="block hover:text-blue-200 transition duration-300">Home</a></li>
            <li><a href="../index.php" class="block hover:text-blue-200 transition duration-300">All Courses</a></li>
        </ul>
    </aside>

    <main class="w-3/4 p-8 relative">
        <a href="../conexions/logout.php" class="absolute top-4 right-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Logout</a>

        <div class="bg-white p-6 rounded-lg shadow-lg mb-6 border border-blue-400">
            <h1 class="text-2xl font-bold text-blue-600">Welcome, <?= htmlspecialchars($username) ?></h1>
            <p>Your subscribed courses are listed below.</p>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Subscribed Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                try {
                    // Fetch subscribed courses for the logged-in student
                    $stmt = $conn->prepare("
                        SELECT c.id, c.title, c.content, c.image 
                        FROM student_courses sc
                        JOIN courses c ON sc.course_id = c.id
                        WHERE sc.student_id = ? AND sc.active = 1
                    ");
                    $stmt->execute([$student_id]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result):
                        foreach ($result as $row): ?>
                            <div class="bg-white p-4 rounded-lg shadow-lg border border-blue-300">
                                <h3 class="text-lg font-bold text-blue-600"><?= htmlspecialchars($row['title']); ?></h3>
                                <p class="text-gray-600 mt-4"><?= htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></p>
                                <a href="<?= htmlspecialchars($row['image']); ?>" class="text-blue-700 hover:text-blue-900 mt-4 block" download>Download Course</a>
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to unsubscribe from this course?');">
                                    <input type="hidden" name="unsubscribe_course_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="px-4 py-2 mt-4 bg-red-600 text-white rounded hover:bg-red-700">Unsubscribe</button>
                                </form>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p class="text-gray-600">You haven't subscribed to any courses yet.</p>
                    <?php endif;

                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
