<?php  
require 'course.php';
session_start();  


// if (!isset($_SESSION['enseignant_id'])) {
//     header('Location: login.php');
//     exit();
// }

$enseignant_id = $_SESSION['enseignant_id'];
$username = $_SESSION['username']; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_title = $_POST['course_title'];
    $course_description = $_POST['course_description'];
    $course_tags = $_POST['course_tags'];
    $file = $_FILES['course_file'];

    // Validate file type (allow only PDF and video)
    $allowed_types = ['application/pdf', 'video/mp4', 'video/avi', 'video/mkv', 'video/mov'];
    if (in_array($file['type'], $allowed_types)) {
        // Process file upload
        $upload_dir = '../uploads/';
        $file_path = $upload_dir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Add course to database
            $courseClass = new Course($conn);
            $courseClass->addCourse($course_title, $course_description, $file_path, $enseignant_id);

            // Optionally, handle tags
            $tags = explode(',', $course_tags);
            foreach ($tags as $tag) {
                $courseClass->addTag($course_id, trim($tag));
            }

            echo "<script>Swal.fire('Success!', 'Course added successfully!', 'success');</script>";
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
                    Tags (comma-separated):  
                    <input type="text" name="course_tags" placeholder="E.g., math, science, programming" class="w-full p-2 rounded bg-gray-100 border border-green-300 text-gray-800 mt-1">  
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
                    $stmt = $conn->prepare("SELECT * FROM courses WHERE id_users = ?");  
                    $stmt->execute([$enseignant_id]);  
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  

                    if ($result):  
                        foreach ($result as $row): ?>  
                            <div class="bg-white p-4 rounded-lg shadow-lg border border-green-300">  
                                <h3 class="text-lg font-bold text-green-600"><?php echo htmlspecialchars($row['title']); ?></h3>  
                                <p class="text-gray-600 mt-4"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>  
                                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="text-green-700 hover:text-green-900 mt-4 block" download>Download Course</a>  
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
