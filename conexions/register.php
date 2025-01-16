<?php
require 'connect.php'; 
require 'Human.php';
require 'User.php';  
session_start();

class Register {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($username, $email, $password) {
        // Check if the email already exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "Email already in use.";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Set the default role to 'user' (role id = 1)
        $default_role_id = 1; // '1' is for 'user' role

        // Insert user into the database
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, id_role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password, $default_role_id])) {
            $user_id = $this->conn->lastInsertId();

            // Store user info in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';

            // Redirect to the profile page
            header("Location: ../Profile/profile.php");
            exit;
        } else {
            return "Error registering user. Please try again.";
        }
    }
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($username)) {
        $register = new Register($conn);
        $error_message = $register->registerUser($username, $email, $password);
    } else {
        $error_message = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-400 to-green-200 text-gray-800">
    <div class="flex min-h-screen justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-3xl font-bold text-green-600 text-center mb-6">Create an Account</h2>

            <?php if (!empty($error_message)): ?>
                <div class="mb-4 text-red-500 text-center"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-medium">Username</label>
                    <input type="text" name="username" id="username" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <button type="submit" 
                    class="w-full py-2 bg-green-500 text-white rounded-md font-medium hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">Already have an account? 
                <a href="login.php" class="text-green-600 hover:underline">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
