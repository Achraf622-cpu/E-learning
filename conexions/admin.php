<?php
// Admin.php
require_once 'Human.php';

class Admin extends Human {
    private $conn;

    public function __construct($id, $name, $email, $conn) {
        parent::__construct($id, $name, $email);
        $this->conn = $conn;
    }

    public function createRole($roleName) {
        $stmt = $this->conn->prepare("INSERT INTO roles (name) VALUES (?)");
        $stmt->execute([$roleName]);
    }

    public function deleteUser($userId) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function manageArticle($action, $articleId) {
        if ($action === 'delete') {
            $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$articleId]);
        }
    }
}
?>
