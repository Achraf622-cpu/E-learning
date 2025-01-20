<?php
require 'connect.php';

class Login extends Connection {

    public function authenticate($email, $password) {
        // Get user info along with the role
        $stmt = $this->conn->prepare("SELECT u.id, u.password, u.username, r.name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['name'];
            $_SESSION['username'] = $user['username'];

            // Redirect based on the role
            if ($user['name'] === 'admin') {
                header("Location: ../admin/admin.php");
                exit;
            } else {
                header("Location: ../Profile/profile.php");
                exit;
            }
        } else {
            return "Invalid email or password.";
        }
    }
}
?>

