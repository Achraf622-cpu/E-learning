<?php
require_once 'Human.php';

class Admin extends User {
    private $conn;

    public function __construct($id, $name, $conn) {
        $this->user_id = $id;       
        $this->username = $name;       
        $this->conn = $conn;       
    }

    public function getAllUsers() {
        $sql = "SELECT u.id, u.username, u.email, r.name AS role 
                FROM users u
                JOIN roles r ON u.role_id = r.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loadUserData(): string {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$this->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? json_encode($user) : json_encode([]);
    }

    public function validateTeacherAccount($approvalRequestId) {
        // Approve a teacher account
        $stmt = $this->conn->prepare(
            "UPDATE approval_requests 
             SET status = 'approved' 
             WHERE id = ?"
        );
        $stmt->execute([$approvalRequestId]);

        // Promote the user to 'enseignant' role after approval
        $stmt = $this->conn->prepare(
            "UPDATE users 
             SET role_id = (SELECT id FROM roles WHERE name = 'enseignant') 
             WHERE id = (SELECT user_id FROM approval_requests WHERE id = ?)"
        );
        $stmt->execute([$approvalRequestId]);
    }

    public function rejectTeacherAccount($approvalRequestId) {
        // Reject a teacher account request
        $stmt = $this->conn->prepare(
            "UPDATE approval_requests 
             SET status = 'rejected' 
             WHERE id = ?"
        );
        $stmt->execute([$approvalRequestId]);
    }

    public function activateUser($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function suspendUser($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function deleteUser($userId) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function manageCategory($action, $categoryId = null, $categoryName = null) {
        if ($action === 'delete') {
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$categoryId]);
        } elseif ($action === 'add') {
            $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$categoryName]);
        }
    }

    public function bulkInsertTags($tags) {
        foreach ($tags as $tag) {
            $stmt = $this->conn->prepare("INSERT INTO tags (tag) VALUES (?)");
            $stmt->execute([$tag]);
        }
    }

    public function deleteCourse($courseId) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$courseId]);
    }

    public function getCourses($filter_date = '', $sort_order = 'recent') {
        $query = "SELECT * FROM courses";

        if (!empty($filter_date)) {
            $query .= " WHERE date >= :filter_date";
        }

        if ($sort_order === 'recent') {
            $query .= " ORDER BY date DESC";
        } else {
            $query .= " ORDER BY date ASC";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($filter_date)) {
            $stmt->bindParam(':filter_date', $filter_date);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCountByRole($roleName) {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE r.name = ?"
        );
        $stmt->execute([$roleName]);
        return $stmt->fetchColumn();
    }

    public function getTotalCourses() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM courses");
        return $stmt->fetchColumn();
    }

    public function getPendingApprovalRequests() {
        $stmt = $this->conn->query(
            "SELECT ar.id, u.username, u.email, ar.created_at 
             FROM approval_requests ar
             JOIN users u ON ar.user_id = u.id 
             WHERE ar.status = 'pending'"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
