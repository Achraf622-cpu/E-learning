<?php  
require '../conexions/connect.php';  
session_start();  

if (!isset($_SESSION['user_id'])) {  
    header("Location: ../conexions/login.php");  
    exit;  
}  

$user_id = $_SESSION['user_id'];  
$username = $_SESSION['username'];  

// Handle new course submission  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    $course_title = htmlspecialchars($_POST['course_title']);  
    $course_description = htmlspecialchars($_POST['course_description']);  
    $course_tags = htmlspecialchars($_POST['course_tags']);  
    $course_file = $_FILES['course_file'];  

    if ($course_file['error'] == 0) {  
        $allowed_types = ['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];  
        if (in_array($course_file['type'], $allowed_types)) {  
            $file_path = '../courses/' . basename($course_file['name']);  
            move_uploaded_file($course_file['tmp_name'], $file_path);  
        } else {  
            echo "Invalid file type. Only PDF and PPT files are allowed.";  
            exit;  
        }  
    } else {  
        $file_path = null;  
    }  

    try {  
        $stmt = $conn->prepare("INSERT INTO courses (title, description, file_path, id_users) VALUES (?, ?, ?, ?)");  
        $stmt->execute([$course_title, $course_description, $file_path, $user_id]);  
        $course_id = $conn->lastInsertId();  

        // Handling tags  
        $tags_array = array_filter(array_map('trim', explode(',', $course_tags)));  
        foreach ($tags_array as $tag) {  
            $stmt_tag = $conn->prepare("SELECT id FROM tags WHERE tag = ?");  
            $stmt_tag->execute([$tag]);  
            $result_tag = $stmt_tag->fetch(PDO::FETCH_ASSOC);  

            if (!$result_tag) {  
                $stmt_insert_tag = $conn->prepare("INSERT INTO tags (tag) VALUES (?)");  
                $stmt_insert_tag->execute([$tag]);  
                $tag_id = $conn->lastInsertId();  
            } else {  
                $tag_id = $result_tag['id'];  
            }  

            // Linking course with tag  
            $stmt_tag_course = $conn->prepare("INSERT INTO course_tags (course_id, tag_id) VALUES (?, ?)");  
            $stmt_tag_course->execute([$course_id, $tag_id]);  
        }  

        header("Location: " . $_SERVER['PHP_SELF']);  
        exit();  

    } catch (PDOException $e) {  
        echo "Error: " . $e->getMessage();  
        exit;  
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
                    Upload Course File:  
                    <input type="file" name="course_file" class="block w-full text-gray-800 border border-green-300 bg-gray-100 mt-1">  
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
                    $stmt->execute([$user_id]);  
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
