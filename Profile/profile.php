<?php  
require 'course.php';
require '../conexions/connect.php';
session_start();

$conn = new Connection();
$enseignant_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Create an instance of the Course class
$courseClass = new Course($conn);

// Handle course deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course_id'])) {
    try {
        $course_id = $_POST['delete_course_id'];

        // Delete the course and associated tags using the OOP methods
        $courseClass->deleteCourse($course_id, $enseignant_id);

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Error!', 'Failed to delete the course.', 'error');</script>";
    }
}

// Handle form submission to add a course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_title'])) {
    $course_title = $_POST['course_title'];
    $course_description = $_POST['course_description'];
    $course_tags = $_POST['course_tags']; // Tags from the form
    $file = $_FILES['course_file']; // PDF file upload
    $video_url = $_POST['video_url']; // Video URL input

    // Validate file type (allow only PDF for file upload)
    $allowed_types = ['application/pdf'];
    $file_path = '';

    if (!empty($file['name']) && in_array($file['type'], $allowed_types)) {
        // Process PDF file upload
        $upload_dir = '../uploads/';
        $file_path = $upload_dir . basename($file['name']);

        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            echo "<script>Swal.fire('Error!', 'File upload failed.', 'error');</script>";
            $file_path = '';
        }
    }

    try {
        // Add the course using the OOP method
        $course_id = $courseClass->addCourse($course_title, $course_description, $file_path, $enseignant_id, $video_url);

        // Handle tags - store course-tags relationships using OOP methods
        $tags = explode(',', $course_tags);

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Error!', 'Failed to add the course.', 'error');</script>";
    }
}

// Fetch available tags from the database using the OOP method
$tags = $courseClass->getAllTags();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-green-400 via-white to-green-200 text-gray-800">
<div class="flex min-h-screen">
    <aside class="w-1/4 bg-gradient-to-b from-green-500 to-green-600 p-6 border-r border-green-300 text-white">
        <h2 class="text-3xl font-extrabold mb-6">Teacher Menu</h2>
        <ul class="space-y-6">
            <li><a href="#" class="block hover:text-green-200 transition duration-300">Profile</a></li>
            <li><a href="../index.php" class="block hover:text-green-200 transition duration-300">Home</a></li>
            <li><a href="#" class="block hover:text-green-200 transition duration-300">Navigate Courses</a></li>
        </ul>
    </aside>

    <main class="w-3/4 p-8 relative">
        <a href="../conexions/logout.php" class="absolute top-4 right-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Logout</a>

        <div class="bg-white p-6 rounded-lg shadow-lg mb-6 border border-green-400">
            <h1 class="text-2xl font-bold text-green-600">Welcome, <?= htmlspecialchars($username) ?></h1>
            <p>Your recent activities are listed below.</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg mb-6 border border-green-400">
            <h2 class="text-xl font-bold text-green-600 mb-4">Add New Course</h2>
            <form method="POST" enctype="multipart/form-data">
                <label class="block text-gray-700 mb-2">
                    Course Title:
                    <input type="text" name="course_title" placeholder="Enter the course title" class="w-full p-2 rounded bg-gray-100 border border-green-300 text-gray-800 mt-1" required>
                </label>
                <label class="block text-gray-700 mb-2">
                    Course Description:
                    <textarea name="course_description" placeholder="Write a description for the course..." class="w-full p-2 rounded bg-gray-100 border border-green-300 text-gray-800 mt-1" required></textarea>
                </label>
                <label class="block text-gray-700 mb-2">
                    Tags:
                    <select name="course_tags[]" class="w-full p-2 rounded bg-gray-100 border border-green-300 text-gray-800 mt-1" multiple required>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?= htmlspecialchars($tag['tag']); ?>"><?= htmlspecialchars($tag['tag']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block text-gray-700 mb-4">
                    Upload Course File (PDF only):
                    <input type="file" name="course_file" class="block w-full text-gray-800 border border-green-300 bg-gray-100 mt-1" accept=".pdf">
                </label>
                <label class="block text-gray-700 mb-4">
                    Video URL (e.g., YouTube):
                    <input type="text" name="video_url" placeholder="Enter video URL" class="w-full p-2 rounded bg-gray-100 border border-green-300 text-gray-800 mt-1">
                </label>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Submit Course</button>
            </form>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold text-green-600 mb-4">Your Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                try {
                    // Fetch courses created by the logged-in teacher using the OOP method
                    $courses = $courseClass->getAllCourses($enseignant_id);

                    if ($courses):
                        foreach ($courses as $row): ?>
                            <div class="bg-white p-4 rounded-lg shadow-lg border border-green-300">
                                <h3 class="text-lg font-bold text-green-600"><?= htmlspecialchars($row['title']); ?></h3>
                                <p class="text-gray-600 mt-4"><?= htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></p>

                                <!-- Show PDF download link -->
                                <?php if ($row['image']): ?>
                                    <a href="<?= htmlspecialchars($row['image']); ?>" class="text-green-700 hover:text-green-900 mt-4 block" download>Download PDF</a>
                                <?php endif; ?>

                                <!-- Show video link -->
                                <?php if ($row['video_url']): ?>
                                    <a href="<?= htmlspecialchars($row['video_url']); ?>" target="_blank" class="text-green-700 hover:text-green-900 mt-4 block">Watch Video</a>
                                <?php endif; ?>

                                <!-- Delete button -->
                                <form method="POST" class="mt-4">
                                    <input type="hidden" name="delete_course_id" value="<?= htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        <?php endforeach;
                    else:
                        echo "<p>No courses found.</p>";
                    endif;
                } catch (PDOException $e) {
                    echo "<script>Swal.fire('Error!', 'Failed to fetch courses.', 'error');</script>";
                }
                ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
