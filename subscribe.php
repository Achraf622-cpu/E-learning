<?php
require './conexions/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    $user_role = $_SESSION['role'] ?? null;

    if (!$course_id || !$user_id || $user_role !== 'student') {
        header('Location: course_details.php?id=' . $course_id);
        exit;
    }

    try {
        $conn = new Connection();

        // Check if already subscribed
        $stmt = $conn->prepare("SELECT COUNT(*) FROM student_courses WHERE student_id = :student_id AND course_id = :course_id");
        $stmt->execute(['student_id' => $user_id, 'course_id' => $course_id]);
        $isSubscribed = $stmt->fetchColumn();

        if (!$isSubscribed) {
            // Add subscription
            $stmt = $conn->prepare("INSERT INTO student_courses (student_id, course_id, active) VALUES (:student_id, :course_id, TRUE)");
            $stmt->execute(['student_id' => $user_id, 'course_id' => $course_id]);

            $_SESSION['success_message'] = 'You have successfully subscribed to the course!';
        } else {
            $_SESSION['info_message'] = 'You are already subscribed to this course.';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'An error occurred. Please try again later.';
    }

    header('Location: course_details.php?id=' . $course_id);
    exit;
}
?>
