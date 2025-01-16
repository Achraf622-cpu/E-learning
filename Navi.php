<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigate Tags</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">

    
    <div class="flex min-h-screen">

        
        <aside class="w-1/4 bg-gray-800 p-6 border-r border-gray-700">
            <h2 class="text-3xl font-extrabold text-blue-400 mb-6">User Menu</h2>
            <ul class="space-y-6">
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Profile</a></li>
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Home</a></li>
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Navigate Tags</a></li>
            </ul>

            
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-blue-400 mb-4">Navigate Tags</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="#" class="text-blue-500 hover:text-blue-300">Web Development</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">Design</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">Technology</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">JavaScript</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">CSS</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">PHP</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">UI/UX</a>
                    <a href="#" class="text-blue-500 hover:text-blue-300">Python</a>
                </div>
            </div>
        </aside>

        
        <main class="w-3/4 p-8 bg-gray-900">
            <div class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-blue-400 mb-4">Explore Blogs by Tags</h1>
                <p class="text-lg text-gray-300">Click on a tag to explore blogs related to that topic.</p>
            </div>

            
            <div class="mt-10">
                <h2 class="text-2xl font-bold text-blue-400 mb-4">Sample Blog Posts</h2>

                
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg relative mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Learning Web Development</h3>
                    </div>
                    <img src="https://via.placeholder.com/600x300" alt="Blog Image" class="rounded-lg mb-4">
                    <p class="text-gray-400">A beginner's guide to web development, from HTML to advanced JavaScript frameworks. Learn the foundations of creating beautiful and functional websites.</p>
                    <p class="text-gray-500 text-sm mt-2">Tags: Web Development, JavaScript, CSS</p>
                </div>

                
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg relative mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">UI/UX Design Principles</h3>
                    </div>
                    <img src="https://via.placeholder.com/600x300" alt="Blog Image" class="rounded-lg mb-4">
                    <p class="text-gray-400">An overview of important UI/UX principles that designers use to create user-friendly, aesthetically pleasing designs.</p>
                    <p class="text-gray-500 text-sm mt-2">Tags: UI/UX, Design</p>
                </div>

                
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg relative mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Introduction to Python</h3>
                    </div>
                    <img src="https://via.placeholder.com/600x300" alt="Blog Image" class="rounded-lg mb-4">
                    <p class="text-gray-400">A comprehensive guide to Python programming, covering basic syntax, functions, and more advanced concepts for data science.</p>
                    <p class="text-gray-500 text-sm mt-2">Tags: Python, Programming</p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
