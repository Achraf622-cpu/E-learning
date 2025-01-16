<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-700 via-green-600 to-green-500 text-white">

<!-- Navbar -->
<nav class="bg-green-900 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-white">Youdemy</a>
        <ul class="flex space-x-6">
            <li><a href="index.php" class="text-white hover:text-gray-300">Home</a></li>
            <li><a href="about.php" class="text-white hover:text-gray-300">About</a></li>
            <li><a href="contact.php" class="text-white hover:text-gray-300">Contact</a></li>
        </ul>
    </div>
</nav>

<!-- Contact Form -->
<div class="container mx-auto py-16 px-4">
    <h1 class="text-5xl font-extrabold text-white mb-8 text-center">Get in Touch</h1>
    <p class="text-center text-gray-200 mb-8">Have a question about our courses or platform? We're here to help!</p>

    <form action="process_contact.php" method="POST" class="bg-white p-8 rounded-lg shadow-lg max-w-xl mx-auto">
        <div class="mb-6">
            <label for="name" class="block text-gray-700 mb-2 font-semibold">Name</label>
            <input type="text" id="name" name="name" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600" required>
        </div>

        <div class="mb-6">
            <label for="email" class="block text-gray-700 mb-2 font-semibold">Email</label>
            <input type="email" id="email" name="email" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600" required>
        </div>

        <div class="mb-6">
            <label for="subject" class="block text-gray-700 mb-2 font-semibold">Subject</label>
            <input type="text" id="subject" name="subject" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600">
        </div>

        <div class="mb-6">
            <label for="message" class="block text-gray-700 mb-2 font-semibold">Message</label>
            <textarea id="message" name="message" rows="4" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600" required></textarea>
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-bold transition duration-300 w-full">
            Send Message
        </button>
    </form>
</div>

</body>
</html>
