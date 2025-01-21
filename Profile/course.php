<?php

class Course {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add a new course
    public function addCourse($title, $content, $image, $enseignant_id) {
        $query = "INSERT INTO courses (title, content, image, enseignant_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $image, $enseignant_id]);
    }

    // Update an existing course
    public function updateCourse($course_id, $title, $content, $image) {
        $query = "UPDATE courses SET title = ?, content = ?, image = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $image, $course_id]);
    }

    // Delete a course
    public function deleteCourse($course_id) {
        $query = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$course_id]);
    }

    // Add a tag to a course
    public function addTag($course_id, $tag) {
        // Check if tag already exists
        $query = "SELECT id FROM tags WHERE tag = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tag]);
        $tag_id = $stmt->fetchColumn();

        if (!$tag_id) {
            // If tag doesn't exist, insert new tag
            $query = "INSERT INTO tags (tag) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$tag]);
            $tag_id = $this->db->lastInsertId();
        }

        // Link course with tag
        $query = "INSERT INTO course_tags (course_id, tag_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$course_id, $tag_id]);
    }

    // Remove a tag from a course
    public function removeTag($course_id, $tag) {
        // Find tag ID
        $query = "SELECT id FROM tags WHERE tag = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tag]);
        $tag_id = $stmt->fetchColumn();

        if ($tag_id) {
            // Remove the link between the course and tag
            $query = "DELETE FROM course_tags WHERE course_id = ? AND tag_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$course_id, $tag_id]);
        }
    }

    // Get all courses for a teacher (enseignant)
    // public function getAllCourses($enseignant_id) {
    //     $query = "SELECT * FROM courses WHERE enseignant_id = ?";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->execute([$enseignant_id]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // Get a course by its ID with tags
    public function getCourseWithTags($course_id) {
        $query = "
            SELECT courses.id AS course_id, courses.title, courses.content, courses.image, 
                   tags.tag
            FROM courses
            LEFT JOIN course_tags ON courses.id = course_tags.course_id
            LEFT JOIN tags ON course_tags.tag_id = tags.id
            WHERE courses.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        // Prepare query to fetch course details
        $stmt = $this->db->prepare("SELECT courses.id, courses.title, courses.content, courses.image, courses.date, courses.file, users.username AS author
                                      FROM courses
                                      JOIN users ON users.id = courses.enseignant_id
                                      WHERE courses.id = ?");
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        return $course ? $course : null;
    }

}
?>
