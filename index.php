<?php
require './conexions/connect.php';
session_start();


$user_role = $_SESSION['role'] ?? null; // Assume 'role' is stored in the session

$query = "SELECT c.id, c.titre, c.para, c.img, c.date, u.username AS author 
          FROM courses c 
          JOIN users u ON c.id_users = u.id 
          ORDER BY c.date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC); // Get results as an associative array

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'], $_POST['comment_text'])) {
    $course_id = intval($_POST['course_id']);
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id && !empty($comment_text)) {
        // Use PDO to insert the comment
        $stmt = $conn->prepare("INSERT INTO comments (text, id_user, id_course) VALUES (?, ?, ?)");
        $stmt->execute([$comment_text, $user_id, $course_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
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
            <li><a href="index.php" class="text-white hover:text-green-100">Home</a></li>
            <li><a href="courses.php" class="text-white hover:text-green-100">Courses</a></li>
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

                // Use PDO to fetch comments for each course
                $stmt = $conn->prepare("SELECT c.text, c.date, u.username 
                                        FROM comments c 
                                        JOIN users u ON c.id_user = u.id 
                                        WHERE c.id_course = ? 
                                        ORDER BY c.date DESC LIMIT 3");
                $stmt->execute([$course['id']]);
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <img src="<?php echo htmlspecialchars($course['img']); ?>" alt="Course Image" class="w-full h-40 object-cover rounded-md mb-4">
                    <h3 class="text-2xl font-bold text-green-600 mb-2"><?php echo htmlspecialchars($course['titre']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($course['para'], 0, 100)) . '...'; ?></p>
                    <p class="text-gray-500 text-sm mb-4">By: <?php echo htmlspecialchars($course['author']); ?></p>
                    <p class="text-gray-400 text-sm mb-4">Published on: <?php echo htmlspecialchars($course['date']); ?></p>

                    <div class="bg-green-50 p-4 rounded-md mb-4">
                        <h4 class="text-xl font-bold text-green-800 mb-2">Comments</h4>
                        <?php
                        if (!empty($comments)) {
                            foreach ($comments as $comment) {
                                ?>
                                <div class="mb-2">
                                    <p class="text-gray-600 text-sm">
                                        <span class="text-green-700 font-bold"><?php echo htmlspecialchars($comment['username']); ?>:</span>
                                        <?php echo htmlspecialchars($comment['text']); ?>
                                    </p>
                                    <p class="text-gray-500 text-xs"><?php echo htmlspecialchars($comment['date']); ?></p>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p class='text-gray-500'>No comments yet.</p>";
                        }
                        ?>
                        <a href="course_details.php?id=<?php echo $course['id']; ?>" class="text-green-700 hover:underline text-sm">View All Comments</a>
                    </div>

                    <?php if (in_array($user_role, ['admin', 'enseignant', 'etudiant'])) { ?>
                        <a href="course_details.php?id=<?php echo $course['id']; ?>" 
                           class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-300 inline-block mt-4">
                           Access Course
                        </a>
                    <?php } else { ?>
                        <p class="text-gray-500 text-sm">Login as a student or teacher to access this course.</p>
                    <?php } ?>

                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <textarea name="comment_text" rows="2" class="w-full text-gray-900 p-2 rounded mb-2" placeholder="Add a comment..." required></textarea>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-300">
                                Submit
                            </button>
                        </form>
                    <?php } else { ?>
                        <p class="text-gray-500 text-sm">Login to add a comment.</p>
                    <?php } ?>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-gray-500 col-span-3'>No courses found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
