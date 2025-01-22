<?php
require './conexions/connect.php';
require './Profile/course.php';
session_start();
$conn = new Connection();
$course = new Course($conn);
$user_role = $_SESSION['role'] ?? null;
$limit = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_courses = $course->getTotalCourses();
$total_pages = ceil($total_courses / $limit);
$courses = $course->getAllCourses($limit, $offset);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-200 via-white to-green-300 text-gray-900">
<nav class="bg-green-500 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-white">YouDemy</a>
        <ul class="flex space-x-6">
            <li><a href="home.php" class="text-white hover:text-green-100">Home</a></li>
            <li><a href="index.php" class="text-white hover:text-green-100">Courses</a></li>
            <li><a href="./Profile/verification.php" class="text-white hover:text-green-100">Profile</a></li>
            <li><a href="about.php" class="text-white hover:text-green-100">About</a></li>
            <li><a href="contact.php" class="text-white hover:text-green-100">Contact</a></li>
        </ul>
    </div>
</nav>
<div class="min-h-screen p-8">
    <h1 class="text-5xl font-extrabold text-green-700 mb-10 text-center">Courses</h1>

    <div class="grid grid-cols-3 gap-6">
        <?php
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
                $author = $stmt->fetch(PDO::FETCH_ASSOC)['username'] ?? 'Unknown';
                $imageSrc = '';
                if (!empty($course['image'])) {
                    $file_extension = strtolower(pathinfo($course['image'], PATHINFO_EXTENSION));
                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $imageSrc = htmlspecialchars($course['image']);
                    } else {
                        $imageSrc = 'img/pdf.png';
                    }
                } else {
                    $imageSrc = 'path/to/placeholder-image.png';
                }
                $date = isset($course['date']) && !empty($course['date']) ? date('F j, Y', strtotime($course['date'])) : 'No Date Available';
                ?>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <img src="<?php echo $imageSrc; ?>" alt="Course Image" class="w-full h-40 object-cover rounded-md mb-4">
                    <h3 class="text-2xl font-bold text-green-600 mb-2"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($course['content'], 0, 100)) . '...'; ?></p>
                    <p class="text-gray-500 text-sm mb-4">By: <?php echo htmlspecialchars($author); ?></p>
                    <p class="text-gray-400 text-sm mb-4">Published on: <?php echo htmlspecialchars($date); ?></p>
                    <?php if (in_array($user_role, ['admin', 'enseignant', 'student'])) { ?>
                        <a href="course_details.php?id=<?php echo $course['id']; ?>" 
                           class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-300 inline-block mt-4">
                           Access Course
                        </a>
                    <?php } else { ?>
                        <p class="text-gray-500 text-sm">Login as a student or teacher to access this course.</p>
                    <?php } ?>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-gray-500 col-span-3'>No courses found.</p>";
        }
        ?>
    </div>
    <!-- Pagination -->
    <div class="mt-10 flex justify-center">
        <?php if ($total_pages > 1): ?>
            <nav class="inline-flex">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-green-500 text-white rounded-l hover:bg-green-600">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="px-4 py-2 border border-green-500 <?php echo $i == $page ? 'bg-green-500 text-white' : 'text-green-500'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-green-500 text-white rounded-r hover:bg-green-600">Next</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
