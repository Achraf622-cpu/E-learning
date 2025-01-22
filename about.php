<?php
// about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-900 to-green-800 text-white">
<nav class="bg-green-800 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-mint-300">My Blog</a>
        <ul class="flex space-x-6">
            <li><a href="index.php" class="text-white hover:text-mint-300">Home</a></li>
            <li><a href="./Profile/verification.php" class="text-white hover:text-mint-300">Profile</a></li>
            <li><a href="index.php" class="text-white hover:text-mint-300">Courses</a></li>
            <li><a href="about.php" class="text-white hover:text-mint-300">About</a></li>
            <li><a href="contact.php" class="text-white hover:text-mint-300">Contact</a></li>
        </ul>
    </div>
</nav>
<div class="container mx-auto py-16 px-4">
    <h1 class="text-4xl font-extrabold text-mint-300 mb-8 text-center">About Us</h1>
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-4">Our Mission</h2>
        <p class="text-gray-200">Welcome to My Blog! Our mission is to share insightful content that inspires, educates, and connects people with their passions. We aim to provide a platform for authentic stories, expert advice, and a vibrant community of readers.</p>
    </section>
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-4">Meet the Team</h2>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-green-700 p-6 rounded-lg shadow-lg">
                <img src="./img/Clown.png" alt="Team Member" class="w-full h-40 object-cover rounded-md mb-4">
                <h3 class="text-xl font-bold text-mint-300">Achraf Hanzaz</h3>
                <p class="text-gray-200">Tech Writer passionate about web development and innovative solutions.</p>
            </div>
            <div class="bg-green-700 p-6 rounded-lg shadow-lg">
                <img src="./img/6515304fad203_chatgpt.jpeg" alt="Team Member" class="w-full h-40 object-cover rounded-md mb-4">
                <h3 class="text-xl font-bold text-mint-300">ChatGPT</h3>
                <p class="text-gray-200">Our Go-To when you lose hope and the reason to live. Last minute save as always coming strong every time.</p>
            </div>
            <div class="bg-green-700 p-6 rounded-lg shadow-lg">
                <img src="./img/claude-ai9117.logowik.com.jpg" alt="Team Member" class="w-full h-40 object-cover rounded-md mb-4">
                <h3 class="text-xl font-bold text-mint-300">Claude AI</h3>
                <p class="text-gray-200">Everyone needs support—even ChatGPT—so Claude is always here to lend a hand.</p>
            </div>
        </div>
    </section>
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-4">Our Journey</h2>
        <p class="text-gray-200">Launched in 2022, My Blog has grown into a trusted source for thousands of readers worldwide. We are committed to continuous growth and delivering value to our community.</p>
    </section>
    <div class="text-center">
        <a href="contact.php" class="bg-mint-500 hover:bg-mint-600 text-white py-2 px-6 rounded">Contact Us</a>
    </div>
</div>
</body>
</html>
