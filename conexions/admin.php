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
        } else if ($action === 'add') {
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
    public function deleteCourse($Course_id) {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$Course_id]);
    }

    // Example function within Admin class to get courses
public function getCourses($filter_date = '', $sort_order = 'recent') {
    // Prepare the SQL query for fetching courses
    $query = "SELECT * FROM courses"; // Ensure this is the correct table (courses)

    // Filter by date if specified
    if (!empty($filter_date)) {
        $query .= " WHERE date >= :filter_date"; // Assuming date is the column name
    }

    // Sorting logic
    if ($sort_order === 'recent') {
        $query .= " ORDER BY date DESC"; // Assuming 'date' is the column for sorting
    } else {
        $query .= " ORDER BY date ASC";
    }

    // Prepare and execute the query
    $stmt = $this->conn->prepare($query);

    // Bind parameters if needed
    if (!empty($filter_date)) {
        $stmt->bindParam(':filter_date', $filter_date);
    }

    // Execute the query
    $stmt->execute();

    // Fetch all courses
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $courses;
}
public function getUserCountByRole($roleName) {
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = ?");
    $stmt->execute([$roleName]);
    return $stmt->fetchColumn();
}

public function getTotalCourses() {
    $stmt = $this->conn->query("SELECT COUNT(*) FROM courses");
    return $stmt->fetchColumn();
}


}
?>
