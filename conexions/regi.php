<?php
require 'connect.php';
class Register extends Connection {
    public function registerUser($username, $email, $password, $role) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "Email already in use.";
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        if ($role == 'enseignant') {
            $role_id = 1; 
        } elseif ($role == 'admin') {
            $role_id = 2; 
        } else {
            $role_id = 3; 
        }
        if ($stmt->execute([$username, $email, $hashed_password, $role_id])) {
            $user_id = $this->conn->lastInsertId();
            if ($role == 'enseignant') {
                $stmt = $this->conn->prepare("INSERT INTO approval_requests (user_id, status) VALUES (?, 'pending')");
                if ($stmt->execute([$user_id])) {
                    return "Your registration is pending admin approval.";
                } else {
                    return "Error processing your request. Please try again.";
                }
            }
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: ../Profile/profile.php");
            exit;
        } else {
            return "Error registering user. Please try again.";
        }
    }
}
?>
