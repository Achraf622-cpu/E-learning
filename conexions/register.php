<?php
require 'regi.php';  // Include the Register class
session_start();
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == 'admin') {
        $error_message = "You cannot register as an admin directly.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($username)) {
        $register = new Register(); // No need to pass the connection
        $error_message = $register->registerUser($username, $email, $password, $role);
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
                <div class="mb-4">
                    <label for="role" class="block text-gray-700 font-medium">Select Role</label>
                    <select name="role" id="role" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="student">Student</option>
                        <option value="enseignant">Enseignant</option>
                    </select>
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
