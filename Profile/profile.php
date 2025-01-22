<?php
// Include the necessary files
require '../conexions/connect.php';
require 'tags.php';
require 'course.php';

session_start();

$conn = new Connection();
$enseignant_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course_id'])) {
    try {
        $course_id = $_POST['delete_course_id'];
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ? AND enseignant_id = ?");
        $stmt->execute([$course_id, $enseignant_id]);
        $stmt = $conn->prepare("DELETE FROM course_tags WHERE course_id = ?");
        $stmt->execute([$course_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Error!', 'Failed to delete the course.', 'error');</script>";
    }
}


// Create an instance of the Tag class to fetch available tags
$tagClass = new Tag($conn);
$tags = $tagClass->getAllTags();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_title'])) {
    $course_title = $_POST['course_title'];
    $course_description = $_POST['course_description'];
    $selected_tags = $_POST['course_tags']; // Array of selected tag IDs
    $file = $_FILES['course_file'];
    
    // Validate file type (allow only PDF and video)
    $allowed_types = ['application/pdf', 'video/mp4', 'video/avi', 'video/mkv', 'video/mov'];
    if (in_array($file['type'], $allowed_types)) {
        // Process file upload
        $upload_dir = '../uploads/';
        $file_path = $upload_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $courseClass = new Course($conn);
            $course_id = $courseClass->addCourse($course_title, $course_description, $file_path, $enseignant_id);

            // Add selected tags to the course
            foreach ($selected_tags as $tag_id) {
                $courseClass->addTag($course_id, $tag_id);
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<script>Swal.fire('Error!', 'File upload failed.', 'error');</script>";
        }
    } else {
        echo "<script>Swal.fire('Error!', 'Invalid file type. Only PDF and video files are allowed.', 'error');</script>";
    }
}
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
                        Tags (Select from available tags):
                        <select name="course_tags[]" multiple class="w-full p-2 rounded bg-gray-100 border border-green-300 text-gray-800 mt-1">
                            <?php foreach ($tags as $tag): ?>
                                <option value="<?= $tag['id'] ?>"><?= htmlspecialchars($tag['tag']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block text-gray-700 mb-4">
                        Upload Course File (PDF/Video only):
                        <input type="file" name="course_file" class="block w-full text-gray-800 border border-green-300 bg-gray-100 mt-1" accept=".pdf, .mp4, .avi, .mkv, .mov" required>
                    </label>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Submit Course</button>
                </form>
            </div>

            <div class="mt-10">
            <h2 class="text-2xl font-bold text-green-600 mb-4">Your Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                try {
                    // Fetch courses created by the logged-in teacher
                    $stmt = $conn->prepare("SELECT * FROM courses WHERE enseignant_id = ?");
                    $stmt->execute([$enseignant_id]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($result):
                        foreach ($result as $row): ?>
                            <div class="bg-white p-4 rounded-lg shadow-lg border border-green-300">
                                <h3 class="text-lg font-bold text-green-600"><?= htmlspecialchars($row['title']); ?></h3>
                                <p class="text-gray-600 mt-4"><?= htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></p>
                                <a href="<?= htmlspecialchars($row['image']); ?>" class="text-green-700 hover:text-green-900 mt-4 block" download>Download Course</a>

                                <!-- Delete Button -->
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <input type="hidden" name="delete_course_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="px-4 py-2 mt-4 bg-red-600 text-white rounded hover:bg-red-700">Delete Course</button>
                                </form>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p class="text-gray-600">No courses found.</p>
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
