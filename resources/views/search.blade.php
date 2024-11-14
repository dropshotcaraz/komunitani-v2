
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pencarian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
</head>
<body>

<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar (if you're using one) -->
        <aside class="w-64 bg-gray-800 text-white hidden md:block">
            <!-- Sidebar content goes here -->
        </aside>
        
        <!-- Main Content with padding and background -->
        <main class="flex-1 flex justify-center p-6">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-3/4 lg:w-2/3">
                <!-- Search Bar -->
                <div class="bg-white p-4 rounded-lg shadow-md mb-8 flex items-center w-full">
                    <input type="text" id="search-input" placeholder="What would you like to search for today?" class="flex-1 p-2 border border-gray-300 rounded-md outline-none">
                    <button id="search-button" class="text-gray-600 p-2">&#128269;</button>
                </div>

                <!-- Search Results Section -->
                <section id="search-results" class="hidden mb-8">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Search Results</h2>
                    <div id="users-results" class="mb-4">
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Users</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
                    </div>
                    <div id="posts-results">
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Posts</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
                    </div>
                </section>

                <!-- Recommendations Section -->
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Recommendations</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic 1</div>
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic 2</div>
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic 3</div>
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic 4</div>
                    </div>
                </section>

                <!-- Popular Section -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Popular</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic A</div>
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic B</div>
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic C</div>
                        <div class="bg-green-100 p-4 text-center font-semibold rounded-md">Topic D</div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('search-button').addEventListener('click', function() {
            // Get the search query
            const query = document.getElementById('search-input').value;
            
            // Check if there's a query
            if (query.trim() === '') {
                alert('Please enter a search term');
                return;
            }

            // Fetch the search results from the API
            fetch(`/api/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Clear previous results
                    const usersContainer = document.getElementById('users-results').querySelector('.grid');
                    const postsContainer = document.getElementById('posts-results').querySelector('.grid');
                    usersContainer.innerHTML = '';
                    postsContainer.innerHTML = '';

                    // Display the results section
                    document.getElementById('search-results').classList.remove('hidden');

                    // Populate users
                    if (data.users && data.users.length > 0) {
                        data.users.forEach(user => {
                            const userDiv = document.createElement('div');
                            userDiv.className = 'bg-blue-100 p-4 text-center font-semibold rounded-md';
                            userDiv.innerText = user.name;
                            usersContainer.appendChild(userDiv);
                        });
                    } else {
                        usersContainer.innerHTML = '<p>No users found</p>';
                    }

                    // Populate posts
                    if (data.posts && data.posts.length > 0) {
                        data.posts.forEach(post => {
                            const postDiv = document.createElement('div');
                            postDiv.className = 'bg-yellow-100 p-4 text-center font-semibold rounded-md';
                            postDiv.innerText = post.content;
                            postsContainer.appendChild(postDiv);
                        });
                    } else {
                        postsContainer.innerHTML = '<p>No posts found</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        });
    </script>
</x-app-layout>

</body>
</html>
