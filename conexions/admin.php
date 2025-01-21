<?php
require_once 'Human.php';

class Admin extends User {
    private $conn;

    public function __construct($id, $name, $email, $conn) {
        $this->user_id = $id;       
        $this->username = $name;  
        $this->email = $email;     
        $this->conn = $conn;       
    }

    public function getAllUsers() {
        // Use the query() method to execute the SQL statement directly
        $sql = "SELECT u.id, u.username, u.email, r.name AS role 
                FROM users u
                JOIN roles r ON u.role_id = r.id";
    
        // Execute the query and fetch all users
        $stmt = $this->conn->query($sql); // Use query() instead of prepare()
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $users;
    }

    public function loadUserData(): string {
        // Load admin data from the database and return it as a JSON string
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$this->user_id]); // Use $this->user_id, inherited from User class
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the user data as a JSON string
        return $user ? json_encode($user) : json_encode([]);
    }

    public function validateTeacherAccount($teacherId) {
        // Validate a teacher account (for 'enseignant' role)
        $stmt = $this->conn->prepare(
            "UPDATE users 
             SET role_id = (SELECT id FROM roles WHERE name = 'enseignant') 
             WHERE id = ?"
        );
        $stmt->execute([$teacherId]);
    }

 
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

}
?>
