<?php
// User.php
require_once 'Human.php';

class User extends Human {
    private $role;
    private $conn;

    public function __construct($id, $name, $email, $role, $conn) {
        parent::__construct($id, $name, $email);
        $this->role = $role;
        $this->conn = $conn;
    }

    public function getRole() {
        return $this->role;
    }

    public function createArticle($title, $content, $image) {
        $stmt = $this->conn->prepare("INSERT INTO articles (titre, para, img, id_users) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $image, $this->id]);
    }

    public function commentOnArticle($articleId, $commentText) {
        $stmt = $this->conn->prepare("INSERT INTO coment (text, id_user, id_article) VALUES (?, ?, ?)");
        $stmt->execute([$commentText, $this->id, $articleId]);
    }
}
?>
