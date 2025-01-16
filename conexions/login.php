<?php
require 'connect.php'; 
session_start();

class Login {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function authenticate($email, $password) {
        // Get user info along with the role
        $stmt = $this->conn->prepare("SELECT u.id, u.password, u.username, r.name FROM users u JOIN roles r ON u.id_role = r.id WHERE u.email = ?");
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

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        $login = new Login($conn);
        $error_message = $login->authenticate($email, $password);
    } else {
        $error_message = "Please enter a valid email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #d4fc79, #96e6a1);
        }

        .form-card {
            background: linear-gradient(145deg, #ffffff, #e0f7df);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .button-green {
            background-color: #38b48b;
            transition: background-color 0.3s, transform 0.3s;
        }

        .button-green:hover {
            background-color: #2a926e;
            transform: scale(1.05);
        }

        .text-green {
            color: #38b48b;
        }

        .input-green {
            border: 2px solid #38b48b;
            color: #333;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-green:focus {
            border-color: #2a926e;
            box-shadow: 0 0 4px #38b48b;
        }
    </style>
</head>
<body class="text-gray-900">
<div class="flex items-center justify-center min-h-screen">
    <div class="form-card w-full max-w-md p-8 rounded-lg">
        <h2 class="text-3xl font-extrabold text-center text-green-500 mb-6">Welcome Back</h2>
        <p class="text-center text-gray-600 mb-4">Log in to your account to access courses and continue learning.</p>
        <?php if (!empty($error_message)): ?>
            <div class="mb-4 text-red-500 text-center">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <input type="email" name="email" placeholder="Email" required 
                       class="w-full px-4 py-2 input-green rounded-md focus:outline-none">
            </div>
            <div class="mb-6">
                <input type="password" name="password" placeholder="Password" required 
                       class="w-full px-4 py-2 input-green rounded-md focus:outline-none">
            </div>
            <button type="submit" class="w-full py-2 button-green text-white rounded-md focus:outline-none">
                Login
            </button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-600">Don't have an account? <a href="register.php" class="text-green-500 hover:text-green-700">Register here</a></p>
    </div>
</div>
</body>
</html>
