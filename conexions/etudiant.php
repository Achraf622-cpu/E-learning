<?php
// Etudiant.php
require_once 'User.php';  // Assuming User is your abstract parent class
class Etudiant extends User {
    private $conn;
    public function __construct($id, $name, $email, $conn) {
        $this->conn = $conn;
        $this->user_id = $id;
        $this->username = $name;
        $this->email = $email;
        
    }

    // Implementation of loadUserData method from the abstract User class
    public function loadUserData(): string {
        // Example of loading student data from the database and returning it as a string
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$this->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the user data as a JSON string
        return json_encode($user);
    }

    // Visualisation du catalogue des cours
    public function viewCourseCatalog() {
        // Retrieve all available courses
        $stmt = $this->conn->prepare("SELECT courses.course_id, courses.title, courses.description, 
                                      users.username AS teacher FROM courses 
                                      JOIN users ON courses.teacher_id = users.user_id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Return all available courses as an array
    }

    // Recherche et consultation des détails des cours
    public function viewCourseDetails($courseId) {
        $stmt = $this->conn->prepare("SELECT courses.title, courses.description, courses.content, 
                                      categories.name AS category, users.username AS teacher 
                                      FROM courses 
                                      JOIN users ON courses.teacher_id = users.user_id
                                      JOIN categories ON courses.category_id = categories.category_id
                                      WHERE courses.course_id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Return the course details as an array
    }

    // Inscription à un cours après authentification
    public function enrollInCourse($courseId) {
        // Ensure the student isn't already enrolled
        $stmt = $this->conn->prepare("SELECT * FROM course_students WHERE student_id = ? AND course_id = ?");
        $stmt->execute([$this->user_id, $courseId]);

        if ($stmt->rowCount() == 0) {
            // Insert the student into the course_students table to enroll them
            $stmt = $this->conn->prepare("INSERT INTO course_students (student_id, course_id) VALUES (?, ?)");
            $stmt->execute([$this->user_id, $courseId]);

            return "You have successfully enrolled in the course.";
        } else {
            return "You are already enrolled in this course.";
        }
    }
    public function myCourses() {
        $stmt = $this->conn->prepare("SELECT courses.course_id, courses.title, courses.description 
                                      FROM courses 
                                      JOIN course_students ON courses.course_id = course_students.course_id
                                      WHERE course_students.student_id = ?");
        $stmt->execute([$this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
