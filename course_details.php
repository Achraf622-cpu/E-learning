<?php
require './conexions/connect.php';
require './Profile/course.php';
session_start();

$conn = new Connection();
$course = new Course($conn);

// Get course ID from URL
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch course details
$courseDetails = $course->getCourseDetails($course_id);

// Check if course exists
if (!$courseDetails) {
    echo "Course not found!";
    exit;
}

// Get user role
$user_role = $_SESSION['role'] ?? null;
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
        <img src="<?php echo htmlspecialchars($courseDetails['image']); ?>" alt="Course Image" class="w-full h-48 object-cover rounded-md mb-4">
        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($courseDetails['content']); ?></p>

        <p class="text-gray-500 mb-4">By: <?php echo htmlspecialchars($courseDetails['author']); ?></p>
        <p class="text-gray-400 mb-4">Published on: <?php echo date('F j, Y', strtotime($courseDetails['date'])); ?></p>
        
        <!-- Show PDF link if a file is available -->
        <?php if (!empty($courseDetails['file'])): ?>
            <a href="<?php echo htmlspecialchars($courseDetails['file']); ?>" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                Download Course Material (PDF)
            </a>
        <?php endif; ?>

        <!-- Check if user is a student or teacher and allow them to access -->
        <?php if (in_array($user_role, ['student', 'enseignant'])): ?>
            <p class="mt-4">You are enrolled in this course. Enjoy learning!</p>
        <?php else: ?>
            <p class="mt-4 text-gray-500">You need to log in as a student or teacher to access the course materials.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
