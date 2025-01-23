<?php
require './conexions/connect.php';
require './Profile/course.php';
session_start();
$conn = new Connection();
$course = new Course($conn);
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$courseDetails = $course->getCourseDetails($course_id);
if (!$courseDetails) {
    echo "Course not found!";
    exit;
}
$user_role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$isSubscribed = false;
if ($user_role === 'student' && $user_id) {
    $stmt = $conn->getConnection()->prepare("SELECT active FROM student_courses WHERE student_id = :student_id AND course_id = :course_id AND active = TRUE");
    $stmt->execute(['student_id' => $user_id, 'course_id' => $course_id]);
    $isSubscribed = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-200 via-white to-green-300 text-gray-900">
<nav class="bg-green-500 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-white">YouDemy</a>
        <ul class="flex space-x-6">
            <li><a href="index.php" class="text-white hover:text-green-100">Home</a></li>
            <li><a href="courses.php" class="text-white hover:text-green-100">Courses</a></li>
            <li><a href="./Profile/verification.php" class="text-white hover:text-green-100">Profile</a></li>
            <li><a href="about.php" class="text-white hover:text-green-100">About</a></li>
            <li><a href="contact.php" class="text-white hover:text-green-100">Contact</a></li>
        </ul>
    </div>
</nav>
<div class="min-h-screen p-8">
    <h1 class="text-4xl font-extrabold text-green-700 mb-8"><?php echo htmlspecialchars($courseDetails['title']); ?></h1>
    <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <?php
        $filePath = $courseDetails['image'] ?? null;

        if ($filePath) {
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                // Display image directly
                echo '<img src="' . htmlspecialchars($filePath) . '" alt="Course Image" class="w-full h-48 object-cover rounded-md mb-4">';
            } elseif (in_array(strtolower($fileExtension), ['pdf', 'mp4', 'avi', 'mkv', 'mov'])) {
                // Display PDF or video placeholder image
                echo '<img src="img/pdf.png" alt="Course Material" class="w-full h-48 object-cover rounded-md mb-4">';
            } else {
                // Default placeholder image
                echo '<img src="img/default.png" alt="Default Image" class="w-full h-48 object-cover rounded-md mb-4">';
            }
        } else {
            echo '<img src="img/default.png" alt="Default Image" class="w-full h-48 object-cover rounded-md mb-4">';
        }
        ?>
        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($courseDetails['content']); ?></p>
        <p class="text-gray-500 mb-4">By: <?php echo htmlspecialchars($courseDetails['author']); ?></p>
        <p class="text-gray-400 mb-4">Published on: <?php echo date('F j, Y', strtotime($courseDetails['date'])); ?></p>

        <!-- Show PDF/Video material in an iframe if subscribed -->
        <?php if ($isSubscribed): ?>
            <?php if ($filePath): ?>
                <?php
                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                if (in_array(strtolower($fileExtension), ['pdf', 'mp4', 'avi', 'mkv', 'mov'])) {
                    // Show PDF/Video in an iframe
                    echo '<iframe src="' . htmlspecialchars($filePath) . '" class="w-full h-96 mt-4" title="Course Material"></iframe>';
                } else {
                    // Show download link for other file types
                    echo '<a href="' . htmlspecialchars($filePath) . '" download class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                            Download Course Material
                          </a>';
                }
                ?>
                <p class="text-green-600 font-semibold mt-4">You are subscribed to this course. Enjoy learning!</p>
            <?php else: ?>
                <p class="text-yellow-500 font-semibold">No downloadable material is associated with this course.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-red-500 font-semibold">Subscribe to access the course material.</p>
            <?php if ($user_role === 'student'): ?>
                <form action="subscribe.php" method="POST" class="mt-4">
                    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course_id); ?>">
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Subscribe
                    </button>
                </form>
            <?php else: ?>
                <p class="mt-4 text-gray-500">Log in as a student to subscribe to this course.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
