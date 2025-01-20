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
        // Assuming the password is being set somewhere, either passed or retrieved
    }

    // Implementation of loadUserData method from the abstract User class
    public function loadUserData(): string {
        // Example of loading user data from a database and returning it as a string
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$this->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the user data as a JSON string
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

    // Gestion des cours : Modification, suppression, et consultation des inscriptions
    public function updateCourse($courseId, $title, $description, $content, $tags, $category) {
        $stmt = $this->conn->prepare("UPDATE courses SET title = ?, description = ?, content = ?, category_id = ? 
                                      WHERE course_id = ? AND teacher_id = ?");
        $stmt->execute([$title, $description, $content, $category, $courseId, $this->user_id]);

        // Update tags for the course
        // First, delete old tags
        $stmt = $this->conn->prepare("DELETE FROM course_tags WHERE course_id = ?");
        $stmt->execute([$courseId]);

        // Add new tags
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

    // Accès à des statistiques sur les cours
    public function getCourseStatistics($courseId) {
        // Number of students enrolled in a course
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM course_students WHERE course_id = ?");
        $stmt->execute([$courseId]);
        $numStudents = $stmt->fetchColumn();

        // Number of courses taught by the teacher
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM courses WHERE teacher_id = ?");
        $stmt->execute([$this->user_id]);
        $numCourses = $stmt->fetchColumn();

        return [
            'num_students' => $numStudents,
            'num_courses' => $numCourses
        ];
    }
}
?>
