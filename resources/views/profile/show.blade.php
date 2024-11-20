<x-app-layout>
    <div class="container bg-gradient-to-r from-[#6FA843] to-[#F7F0CF] rounded-xl mx-auto max-w-6xl my-6 px-4 py-8">
        <div class="relative w-full shadow-xl">

    <!-- Profile Header -->
        <div class="relative bg-gradient-to-r from-[#F7F0CF] to-[#FFFFFF] rounded-xl shadow-lg overflow-hidden">
            <!-- Cover Photo -->
            <div class="h-64 relative">
                @if($user->cover_photo)
                    <img src="{{ asset("storage/{$user->cover_photo}") }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-[#434028] opacity-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-[#618805]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                @endif
                
                <!-- Edit Profile Button -->
                @if($isCurrentUser )
                <a href="{{ route('profile.edit') }}" class="absolute top-4 right-4 bg-[#314502] text-white px-4 py-2 rounded-full hover:bg-[#434028] transition duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.379-8.379-2.828-2.828z" />
                    </svg>
                    Edit Profile
                </a>
                @endif
            </div>

            <div class="flex items-center p-4 bg-white bg-opacity-80 backdrop-blur-sm">
                <div class="relative">
                    <img src="{{ $user->profile_picture ? asset("storage/{$user->profile_picture}") : asset('https://xsgames.co/randomusers/avatar.php?g=pixel') }}" 
                        class="w-32 h-32 rounded-full border-4 border-[#618805] object-cover shadow-lg">
                </div>
                
                <div class="ml-6 flex-grow flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-[#434028]">{{ $user->name }}</h1>
                        <p class="text-[#618805]">{{ $user->email }}</p>
                        <p class="text-gray-700 mt-2 italic flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#618805]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $user->bio ?? 'No bio yet' }}
                        </p>
                        <div class="mt-4">
                            <span class="font-bold">{{ $user->followers_count }} Followers</span> |
                            <span class ="font-bold">{{ $user->following_count }} Following</span>
                        </div>
                    </div>
                    <div class="ml-4 pr-6 flex gap-2">
                        @if(Auth::check() && Auth::user()->id !== $user->id)
                            <a href="/messages/{{ $user->id }}" class="bg-[#618805] text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                Message
                            </a>
                            
                            @if(!Auth::user()->isFollowing($user->id))
                                <form action="{{ route('follow', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-[#6FA843] text-white px-4 py-2 rounded-lg hover:bg-[#578432] transition">Follow</button>
                                </form>
                            @else
                                <form action="{{ route('unfollow', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Unfollow</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

            <!-- Profile Tabs -->
            <div class="mt-8">
                <div class="flex border-b-2 border-[#618805]">
                    <button class="px-6 py-3 tab-button active text-[#434028] font-semibold hover:bg-[#F7F0CF] transition" data-tab="posts">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Posts
                    </button>
                    <button class="px-6 py-3 tab-button text-[#434028] font-semibold hover:bg-[#F7F0CF] transition" data-tab="likes">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Likes
                    </button>
                </div>

                <!-- Posts Tab -->
                <div id="posts-tab" class="tab-content bg-white rounded-b-xl shadow-md py-4">
                @foreach ($posts as $post)
                @include('posts.partials.post-card')
                @endforeach
                </div>


                <!-- Likes Tab -->
                <div id="likes-tab" class="tab-content hidden bg-white rounded-b-xl shadow-md py-4">
                @foreach ($likedPosts as $likedPost)
                    @include('posts.partials.post-card', ['post' => $likedPost])
                @endforeach
                </div>
                </div>
            </div>
        </div>

        @include('posts.partials.post-button')

        <!-- Image Modal -->
        <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-70 hidden transition-opacity duration-500"></div>
        <div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden transition-transform duration-500 transform scale-75">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <img id="modalImage" src="" alt="Modal Image" class="rounded-lg">
                <button onclick="closeImageModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-[#434028] transition-colors">Close</button>
            </div>
        </div>

        <script>
            // Tab switching logic
        
                document.addEventListener('DOMContentLoaded', () => {
                    const defaultTab = document.querySelector('.tab-button[data-tab="posts"]');
                    defaultTab.classList.add('active', 'mx-4', 'bg-[#F7F0CF]', 'border', 'border-[#618805]', 'rounded-t-xl');
                    document.getElementById('posts-tab').classList.remove('hidden');

                    document.querySelectorAll('.tab-button').forEach(button => {
                        button.addEventListener('click', () => {
                            const tab = button.getAttribute('data-tab');

                            document.querySelectorAll('.tab-button').forEach(b => {
                                b.classList.remove('active', 'bg-[#F7F0CF]', 'border', 'border-[#618805]', 'rounded-t-xl');
                            });

                            document.querySelectorAll('.tab-content').forEach(content => {
                                content.classList.add('hidden');
                            });

                            button.classList.add('active', 'bg-[#F7F0CF]', 'border', 'border-[#618805]', 'rounded-t-xl');
                            document.getElementById(`${tab}-tab`).classList.remove('hidden');
                        });
                    });
                });

            document.querySelectorAll('.like-button').forEach(button => {
                const postId = button.getAttribute('data-post-id');
                const likeCountElement = button.querySelector('.like-count');
                const heartIcon = button.querySelector('.heart-icon');

                button.addEventListener('click', function() {
                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({}),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            likeCountElement.textContent = data.likeCount; // Update the like count
                            heartIcon.classList.toggle('text-red-500', data.liked);
                            this.classList.toggle('text-red-500', data.liked);
                        }
                    });
                });
            });

            document.querySelectorAll('.comment-button').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const commentsSection = document.getElementById('comments' + postId);
                    commentsSection.classList.toggle('hidden');
                });
            });
            
        </script>
    </div>
</x-app-layout>