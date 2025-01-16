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
<body class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">

<nav class="bg-gray-800 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-blue-400">My Blog</a>
        <ul class="flex space-x-6">
            <li><a href="index.php" class="text-white hover:text-blue-400">Home</a></li>
            <li><a href="about.php" class="text-white hover:text-blue-400">About</a></li>
            <li><a href="contact.php" class="text-white hover:text-blue-400">Contact</a></li>
        </ul>
    </div>
</nav>

<div class="container mx-auto py-16 px-4">
    <h1 class="text-4xl font-extrabold text-blue-400 mb-8 text-center">About Us</h1>

    <section class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-4">Our Mission</h2>
        <p class="text-gray-300">Welcome to My Blog! Our mission is to share insightful content that inspires, educates, and connects people with their passions. We aim to provide a platform for authentic stories, expert advice, and a vibrant community of readers.</p>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-4">Meet the Team</h2>
        <div class="grid grid-cols-3 gap-6">

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <img src="./uploads/Clown.png" alt="Team Member" class="w-full h-40 object-cover rounded-md mb-4">
                <h3 class="text-xl font-bold text-blue-400">Achraf Hanzaz</h3>
                <p class="text-gray-300">Tech Writer passionate about web development and innovative solutions.</p>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <img src="./uploads/6515304fad203_chatgpt.jpeg" alt="Team Member" class="w-full h-40 object-cover rounded-md mb-4">
                <h3 class="text-xl font-bold text-blue-400">ChatGPT</h3>
                <p class="text-gray-300">Our Go-To when you lose hope and the reason to live. Last minute save as always comming strong everytime.</p>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <img src="./uploads/claude-ai9117.logowik.com.jpg" alt="Team Member" class="w-full h-40 object-cover rounded-md mb-4">
                <h3 class="text-xl font-bold text-blue-400">Claude AI</h3>
                <p class="text-gray-300">Every one needs support even the Chat so Claude is always here to give support.</p>
            </div>


        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-4">Our Journey</h2>
        <p class="text-gray-300">Launched in 2022, My Blog has grown into a trusted source for thousands of readers worldwide. We are committed to continuous growth and delivering value to our community.</p>
    </section>

    <div class="text-center">
        <a href="contact.php" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded">Contact Us</a>
    </div>
</div>

</body>
</html>


