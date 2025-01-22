<?php
class Course {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function addCourse($title, $content, $image, $enseignant_id) {
        $query = "INSERT INTO courses (title, content, image, enseignant_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $image, $enseignant_id]);
    }
    // Delete a course
    public function deleteCourse($course_id) {
        $query = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$course_id]);
    }
    public function addTag($course_id, $tag) {
        // Check if tag already exists
        $query = "SELECT id FROM tags WHERE tag = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tag]);
        $tag_id = $stmt->fetchColumn();

        // if (!$tag_id) {
        //     // If tag doesn't exist, insert new tag
        //     $query = "INSERT INTO tags (tag) VALUES (?)";
        //     $stmt = $this->db->prepare($query);
        //     $stmt->execute([$tag]);
        //     // $tag_id = $this->db->lastInsertId();
        // }

        // Link course with tag
        $query = "INSERT INTO course_tags (course_id, tag_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        // $stmt->execute([$course_id, $tag_id]);
    }
    public function getAllCourses($limit, $offset) {
        $stmt = $this->db->prepare("SELECT c.id, c.title, c.content, c.image, c.date, u.username AS enseignant 
                                     FROM courses c 
                                     JOIN users u ON c.enseignant_id = u.id 
                                      ORDER BY c.date DESC 
                                     LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalCourses() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM courses");
        return $stmt->fetchColumn();
    }
    
    public function getCourseDetails($course_id) {
        $stmt = $this->db->prepare("
            SELECT courses.id, courses.title, courses.content, courses.image, courses.date, users.username AS author
            FROM courses
            JOIN users ON users.id = courses.enseignant_id
            WHERE courses.id = ?
        ");
        $stmt->execute([$course_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkSubscription($course_id, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM student_courses WHERE course_id = ? AND user_id = ? AND active = 1");
        $stmt->execute([$course_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

}
?>
