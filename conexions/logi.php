<?php
require 'connect.php';

class Login extends Connection {

    public function authenticate($email, $password) {

        $stmt = $this->conn->prepare("SELECT u.id, u.password, u.username, r.name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['name'];
            $_SESSION['username'] = $user['username'];


            if ($user['name'] === 'admin') {
                header("Location: ../admin/adminpro.php");
                exit;
            } else if ($user['name'] === 'enseignant') {
                header("Location: ../Profile/profile.php");
                exit;
            } else {
                header("Location: ../Profile/etudiant.php");
                exit;
            }
        } else {
            return "Invalid email or password.";
        }
    }
}
?>

