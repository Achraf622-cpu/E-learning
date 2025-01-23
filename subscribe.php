<?php
require './conexions/connect.php';
session_start();

$conn = new Connection();
$user_id = $_SESSION['user_id'] ?? null;
$course_id = $_POST['course_id'] ?? null;

if (!$user_id || !$course_id) {
    echo "Invalid request.";
    exit;
}

// Check if the user is a student
$user_role = $_SESSION['role'] ?? null;
if ($user_role !== 'student') {
    echo "Only students can subscribe to courses.";
    exit;
}

try {
    // Check if the user is already subscribed
    $stmt = $conn->getConnection()->prepare("SELECT * FROM student_courses WHERE student_id = :student_id AND course_id = :course_id");
    $stmt->execute(['student_id' => $user_id, 'course_id' => $course_id]);
    $existingSubscription = $stmt->fetch();

    if ($existingSubscription) {
        echo "You are already subscribed to this course.";
        exit;
    }

    // Subscribe the user
    $stmt = $conn->getConnection()->prepare("INSERT INTO student_courses (student_id, course_id, active) VALUES (:student_id, :course_id, TRUE)");
    $stmt->execute(['student_id' => $user_id, 'course_id' => $course_id]);

    header("Location: course_details.php?id=" . $course_id);
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
