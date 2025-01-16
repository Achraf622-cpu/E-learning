<?php
require './conexions/connect.php';
session_start();

// Check if the course ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$course_id = intval($_GET['id']);

// Fetch the course details
$query = "SELECT c.id, c.title, c.description, c.image, c.duration, c.price, c.date, u.username AS instructor 
          FROM courses c 
          JOIN users u ON c.instructor_id = u.id 
          WHERE c.id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $course_id, PDO::PARAM_INT);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    echo "<p class='text-gray-400'>Course not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-700 via-green-600 to-green-500 text-white">

<!-- Navbar -->
<nav class="bg-green-900 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-white">E-Learning</a>
        <ul class="flex space-x-6">
            <li><a href="index.php" class="text-white hover:text-gray-300">Home</a></li>
            <li><a href="courses.php" class="text-white hover:text-gray-300">Courses</a></li>
            <li><a href="profile.php" class="text-white hover:text-gray-300">Profile</a></li>
            <li><a href="about.php" class="text-white hover:text-gray-300">About</a></li>
        </ul>
    </div>
</nav>

<div class="min-h-screen p-8">

    <!-- Course Details -->
    <div class="max-w-4xl mx-auto bg-green-800 p-6 rounded-lg shadow-lg">
        <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="Course Image" class="w-full h-60 object-cover rounded-md mb-4">
        <h1 class="text-4xl font-bold text-white mb-4"><?php echo htmlspecialchars($course['title']); ?></h1>
        <p class="text-gray-300 mb-4">Instructor: <span class="font-bold"><?php echo htmlspecialchars($course['instructor']); ?></span></p>
        <p class="text-gray-400 text-sm mb-6">Published on: <?php echo htmlspecialchars($course['date']); ?></p>
        <p class="text-gray-300 leading-relaxed mb-6"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

        <!-- Extra Course Details -->
        <div class="mt-4">
            <p class="text-gray-200 text-lg mb-2"><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
            <p class="text-gray-200 text-lg mb-2"><strong>Price:</strong> $<?php echo htmlspecialchars($course['price']); ?></p>
            <p class="text-gray-200 text-lg mb-4"><strong>Enrollment:</strong> <?php echo $course['price'] > 0 ? 'Paid Course' : 'Free Course'; ?></p>
        </div>

        <!-- Call-to-Action -->
        <a href="enroll.php?course_id=<?php echo $course_id; ?>" class="block text-center bg-white text-green-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-200 transition duration-300">
            Enroll Now
        </a>
    </div>

</div>

</body>
</html>
