<?php
require 'connect.php';

class Register extends Connection {

    public function registerUser($username, $email, $password, $role) {

        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "Email already in use.";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // If the role is "enseignant," insert into approval_requests table
        if ($role == 'enseignant') {
            $stmt = $this->conn->prepare("INSERT INTO approval_requests (username, email) VALUES (?, ?)");
            if ($stmt->execute([$username, $email])) {
                return "Your registration is pending admin approval.";
            } else {
                return "Error processing your request. Please try again.";
            }
        }

        // Set the default role to 'student' (role id = 3 for student)
        $default_role_id = 3; // '3' is for 'student' role

        // Insert user into the database
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, id_role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password, $default_role_id])) {
            $user_id = $this->conn->lastInsertId();

            // Store user info in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'student';

            // Redirect to the profile page
            header("Location: ../Profile/profile.php");
            exit;
        } else {
            return "Error registering user. Please try again.";
        }
    }
}
?>