<?php

class Course {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add a new course
    public function addCourse($title, $content, $image, $enseignant_id, $video_url = '', $file = null) {
        // Handle file upload (if any)
        $file_path = '';
        if ($file && in_array($file['type'], ['application/pdf'])) {
            $upload_dir = '../uploads/';
            $file_path = $upload_dir . basename($file['name']);
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                echo "<script>Swal.fire('Error!', 'File upload failed.', 'error');</script>";
                $file_path = '';
            }
        }

        $query = "INSERT INTO courses (title, content, image, enseignant_id, video_url, file_path) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $image, $enseignant_id, $video_url, $file_path]);
    }

    // Update an existing course
    public function updateCourse($course_id, $title, $content, $image, $video_url = '', $file = null) {
        // Handle file upload (if any)
        $file_path = '';
        if ($file && in_array($file['type'], ['application/pdf'])) {
            $upload_dir = '../uploads/';
            $file_path = $upload_dir . basename($file['name']);
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                echo "<script>Swal.fire('Error!', 'File upload failed.', 'error');</script>";
                $file_path = '';
            }
        }

        $query = "UPDATE courses SET title = ?, content = ?, image = ?, video_url = ?, file_path = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$title, $content, $image, $video_url, $file_path, $course_id]);
    }

    // Delete a course
    public function deleteCourse($course_id) {
        // Delete associated tags first
        $query = "DELETE FROM course_tags WHERE course_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$course_id]);

        // Then delete the course
        $query = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$course_id]);
    }

    // Add a tag to a course
    public function getAllTags() {
        // Query to fetch all tags
        $query = "SELECT id, tag FROM tags";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        // Fetch all tags and return as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get all courses for a teacher (enseignant)
    public function getAllCourses($enseignant_id) {
        $query = "SELECT * FROM courses WHERE enseignant_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$enseignant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a course by its ID with tags
    public function getCourseWithTags($course_id) {
        $query = "
            SELECT courses.id AS course_id, courses.title, courses.content, courses.image, 
                   courses.video_url, courses.file_path,
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

    // Get all available courses for students
    public function getAvailableCourses() {
        $query = "SELECT courses.id, courses.title, courses.content, courses.image, users.username AS enseignant
                  FROM courses
                  JOIN users ON courses.enseignant_id = users.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get the total number of courses
    public function getTotalCourses(): int {
        $query = "SELECT COUNT(*) AS total FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total']; // Ensure it returns an integer
    }
}
?>
