<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">

   
    <div class="flex min-h-screen">

     
        <aside class="w-1/4 bg-gray-800 p-6 border-r border-gray-700">
            <h2 class="text-3xl font-extrabold text-blue-400 mb-6">Admin Menu</h2>
            <ul class="space-y-6">
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Profile</a></li>
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Home</a></li>
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Navigate Tags</a></li>
                <li><a href="#" class="block text-white hover:text-blue-400 transition duration-300">Users</a></li>
            </ul>
        </aside>


        <main class="w-3/4 p-8 bg-gray-900">


            <div class="text-center mb-6">
                <p class="text-lg text-gray-300">You're an admin</p>
            </div>


            <div class="bg-gray-800 p-6 rounded-lg shadow-lg mb-6">
                <div class="flex items-center">
                    <img src="https://via.placeholder.com/100" alt="User Avatar" class="w-20 h-20 rounded-full border-4 border-blue-400">
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold text-blue-400">User Name</h1>
                        <p class="text-gray-400">Email: email@example.com</p>
                        <p class="text-gray-400">Total Posts: XX</p>
                    </div>
                </div>
            </div>


            <div class="flex justify-end mb-6">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add Post
                </button>
            </div>


            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4 text-blue-400">Add a New Post</h2>

                <form>

                    <label class="block text-white mb-2">
                        Title:
                        <input type="text" placeholder="Enter your post title" 
                               class="w-full p-2 rounded bg-gray-700 text-white mt-1">
                    </label>


                    <label class="block text-white mb-2">
                        Description:
                        <textarea placeholder="Write a description for your post..." 
                                  class="w-full p-2 rounded bg-gray-700 text-white mt-1"></textarea>
                    </label>


                    <label class="block text-white mb-2">
                        Tags (comma-separated):
                        <input type="text" placeholder="E.g., coding, design, technology" 
                               class="w-full p-2 rounded bg-gray-700 text-white mt-1">
                    </label>


                    <label class="block text-white mb-4">
                        Upload Image:
                        <input type="file" class="block w-full text-white mt-1">
                    </label>


                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Submit Post
                    </button>
                </form>
            </div>


            <div class="mt-10">
                <h2 class="text-2xl font-bold text-blue-400 mb-4">Your Blogs</h2>


                <div class="bg-gray-800 p-6 rounded-lg shadow-lg relative mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Sample Blog Title</h3>
                        <button class="relative group">
                            <img src="https://via.placeholder.com/40" alt="Options" class="w-8 h-8 rounded-full">
                            <ul class="absolute hidden group-hover:block bg-gray-700 text-white rounded-lg shadow-lg mt-2">
                                <li class="px-4 py-2 hover:bg-gray-600">Modify</li>
                                <li class="px-4 py-2 hover:bg-gray-600">Delete</li>
                            </ul>
                        </button>
                    </div>
                    <img src="https://via.placeholder.com/600x300" alt="Blog Image" class="rounded-lg mb-4">
                    <p class="text-gray-400">This is a brief description of the blog post. It gives a quick overview of what the blog is about.</p>
                    <p class="text-gray-500 text-sm mt-2">Tags: coding, design</p>
                </div>
            </div>


            <div class="mt-10">
                <h2 class="text-2xl font-bold text-blue-400 mb-4">Manage Users</h2>


                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold text-white mb-4">User List</h3>
                    <ul>
                        <li class="text-gray-300">User1 (user1@example.com)</li>
                        <li class="text-gray-300">User2 (user2@example.com)</li>
                        <li class="text-gray-300">User3 (user3@example.com)</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
