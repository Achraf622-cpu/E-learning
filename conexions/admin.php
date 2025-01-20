<?php
// Admin.php
require_once 'User.php';  // Assuming User is your abstract parent class

class Admin extends User {
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
        // Example of loading admin data from the database and returning it as a string
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$this->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the user data as a JSON string
        return json_encode($user);
    }

    // Validation des comptes enseignants
    public function validateTeacherAccount($teacherId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'validated' WHERE user_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'enseignant')");
        $stmt->execute([$teacherId]);
    }

    // Gestion des utilisateurs : Activation, suspension ou suppression
    public function activateUser($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

    public function suspendUser($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'suspended' WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

    public function deleteUser($userId) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

    // Gestion des contenus : Cours, catégories et tags
    public function manageCourse($action, $courseId, $title = null, $description = null, $content = null, $category = null) {
        if ($action === 'delete') {
            $stmt = $this->conn->prepare("DELETE FROM courses WHERE course_id = ?");
            $stmt->execute([$courseId]);
        } elseif ($action === 'update') {
            $stmt = $this->conn->prepare("UPDATE courses SET title = ?, description = ?, content = ?, category_id = ? WHERE course_id = ?");
            $stmt->execute([$title, $description, $content, $category, $courseId]);
        }
    }

    public function manageCategory($action, $categoryId = null, $categoryName = null) {
        if ($action === 'delete') {
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE category_id = ?");
            $stmt->execute([$categoryId]);
        } elseif ($action === 'add') {
            $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$categoryName]);
        }
    }

    // Insertion en masse de tags pour gagner en efficacité
    public function bulkInsertTags($tags) {
        foreach ($tags as $tag) {
            $stmt = $this->conn->prepare("INSERT INTO tags (name) VALUES (?)");
            $stmt->execute([$tag]);
        }
    }

    // Accès à des statistiques globales
    public function getGlobalStatistics() {
        // Total number of courses
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM courses");
        $stmt->execute();
        $totalCourses = $stmt->fetchColumn();

        // Courses by category
        $stmt = $this->conn->prepare("SELECT categories.name, COUNT(*) FROM courses 
                                      JOIN categories ON courses.category_id = categories.category_id
                                      GROUP BY categories.name");
        $stmt->execute();
        $coursesByCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Course with the most students
        $stmt = $this->conn->prepare("SELECT courses.title, COUNT(*) AS num_students FROM courses 
                                      JOIN course_students ON courses.course_id = course_students.course_id
                                      GROUP BY courses.title ORDER BY num_students DESC LIMIT 1");
        $stmt->execute();
        $courseWithMostStudents = $stmt->fetch(PDO::FETCH_ASSOC);

        // Top 3 teachers
        $stmt = $this->conn->prepare("SELECT users.username, COUNT(*) AS num_courses FROM courses 
                                      JOIN users ON courses.teacher_id = users.user_id
                                      GROUP BY users.username ORDER BY num_courses DESC LIMIT 3");
        $stmt->execute();
        $topTeachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'total_courses' => $totalCourses,
            'courses_by_category' => $coursesByCategory,
            'course_with_most_students' => $courseWithMostStudents,
            'top_teachers' => $topTeachers
        ];
    }
}
?>
