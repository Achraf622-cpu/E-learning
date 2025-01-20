<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - YouDemy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-green-200 via-white to-green-300 text-gray-900">

<nav class="bg-green-500 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-white">YouDemy</a>
        <ul class="flex space-x-6">
            <li><a href="home.php" class="text-white hover:text-green-100">Home</a></li>
            <li><a href="courses.php" class="text-white hover:text-green-100">Courses</a></li>
            <li><a href="./Profile/verification.php" class="text-white hover:text-green-100">Profile</a></li>
            <li><a href="about.php" class="text-white hover:text-green-100">About</a></li>
            <li><a href="contact.php" class="text-white hover:text-green-100">Contact</a></li>
        </ul>
    </div>
</nav>

<div class="min-h-screen p-8">
    <h1 class="text-5xl font-extrabold text-green-700 mb-10 text-center">Welcome to YouDemy</h1>

    <div class="mb-10 text-center">
        <p class="text-lg text-gray-600 mb-6">YouDemy is an innovative e-learning platform that offers a wide range of courses for individuals looking to advance their skills and knowledge. Whether you're interested in programming, design, or business, YouDemy has something for everyone!</p>
        <img src="./img/heeeeerooooo.jpg"alt="YouDemy Logo" class="mx-auto rounded-md shadow-lg mb-6">
        <p class="text-lg text-gray-600">Join thousands of learners today and start your journey towards mastery!</p>
    </div>

    <h2 class="text-3xl font-bold text-green-600 mb-6 text-center">Featured Courses</h2>
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <img src="./img/How-to-create-an-online-course.jpg" alt="Course 1" class="w-full h-40 object-cover rounded-md mb-4">
            <h3 class="text-2xl font-bold text-green-600 mb-2">Course Title 1</h3>
            <p class="text-gray-600">Learn the fundamentals of programming with this beginner-friendly course.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <img src="./img/successful-online-courses-featured-image.jpg" alt="Course 2" class="w-full h-40 object-cover rounded-md mb-4">
            <h3 class="text-2xl font-bold text-green-600 mb-2">Course Title 2</h3>
            <p class="text-gray-600">Master web design principles and build stunning websites from scratch.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <img src="" alt="Course 3" class="w-full h-40 object-cover rounded-md mb-4">
            <h3 class="text-2xl font-bold text-green-600 mb-2">Course Title 3</h3>
            <p class="text-gray-600">Explore the world of digital marketing and boost your career with key strategies.</p>
        </div>
    </div>
</div>

</body>
</html>
