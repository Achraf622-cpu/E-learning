<?php
require 'connect.php';

class Login extends Connection {

    public function authenticate($email, $password) {
        // Prepare the query to get the user data based on the email
        $stmt = $this->conn->prepare("SELECT u.id, u.password, u.username, r.name 
                                      FROM users u 
                                      JOIN roles r ON u.role_id = r.id 
                                      WHERE u.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Store user information in session variables
            $_SESSION['user_id'] = $user['id'];         // The user's ID (enseignant_id)
            $_SESSION['role'] = $user['name'];           // The user's role (enseignant/admin/student)
            $_SESSION['username'] = $user['username'];   // The user's username

            // Redirect user based on their role
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
            // Return an error message if authentication fails
            return "Invalid email or password.";
        }
    }
}
?>
