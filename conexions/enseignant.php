<?php
// Enseignant.php
require_once 'User.php';  // Assuming User is your abstract parent class
class Enseignant extends User {
    private $conn;
    public function __construct($id, $name, $email, $conn) {
        $this->conn = $conn;
        $this->user_id = $id;
        $this->username = $name;
        $this->email = $email;
    }
    public function loadUserData(): string {
        // Example of loading user data from a database and returning it as a string
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$this->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($user);
    }

    // Ajout de nouveaux cours avec des détails
    public function addCourse($title, $description, $content, $tags, $category) {
        // Insert course details into the 'courses' table
        $stmt = $this->conn->prepare("INSERT INTO courses (title, description, content, teacher_id, category_id) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $content, $this->user_id, $category]);

        $courseId = $this->conn->lastInsertId();

        // Add tags for the course
        foreach ($tags as $tag) {
            $stmt = $this->conn->prepare("INSERT INTO course_tags (course_id, tag_id) 
                                          VALUES (?, (SELECT id FROM tags WHERE name = ?))");
            $stmt->execute([$courseId, $tag]);
        }
    }

    public function deleteCourse($courseId) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE course_id = ? AND teacher_id = ?");
        $stmt->execute([$courseId, $this->user_id]);
    }

    public function viewEnrollments($courseId) {
        $stmt = $this->conn->prepare("SELECT students.name FROM students 
                                      JOIN course_students ON students.student_id = course_students.student_id
                                      WHERE course_students.course_id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
