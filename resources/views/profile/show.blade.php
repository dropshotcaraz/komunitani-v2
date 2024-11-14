<x-app-layout>
    <div class="container mx-auto max-w-6xl my-6 px-4 py-8">
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
                    <a href="{{ route('profile.edit') }}" class="absolute top-4 right-4 bg-[#314502] text-white px-4 py-2 rounded-full hover:bg-[#434028] transition duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.379-8.379-2.828-2.828z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>

                <div class="flex items-center p-4 bg-white bg-opacity-80 backdrop-blur-sm">
                    <div class="relative">
                        <img src="{{ $user->profile_picture ? asset("storage/{$user->profile_picture}") : asset('images/default-avatar.png') }}" 
                            class="w-32 h-32 rounded-full border-4 border-[#618805] object-cover shadow-lg">
                    </div>
                    
                    <div class="ml-6 flex-grow">
                        <h1 class="text-3xl font-bold text-[#434028]">{{ $user->name }}</h1>
                        <p class="text-[#618805]">{{ $user->email }}</p>
                        <p class="text-gray-700 mt-2 italic flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#618805]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $user->bio ?? 'No bio yet' }}
                        </p>
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
                <div id="posts-tab" class="tab-content bg-white rounded-b-xl shadow-md p-4">
                @foreach($posts as $post)
                <div class="bg-[#F7F0CF] shadow-lg p-6 rounded-3xl mt-6 mb-6 border border-gray-200">
                    <div class="flex items-center justify-right mb-4">
                    @if (isset($post->user->profile_picture) && $post->user->profile_picture)
                        <img src="{{ asset('storage/' . $post->user->profile_picture) }}" alt="{{ $post->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                    @else
                        <img src="{{ asset('images/avatar1.png') }}" alt="{{ $post->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                    @endif
                        <div>
                            <h2 class="font-bold text-[#2D3748]">{{ $post->user->name }}</h2>
                            <p class="text-gray-500 text-sm">{{ $post->created_at->format('d M Y - H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Topic and Edit/Delete Buttons -->
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-bold text-xl text-[#6FA843]">Topik: {{ $post->topic ?? 'Umum' }}</p>
                        @if(auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->is_admin)) 
                        <div class="flex space-x-4">
                            <a href="{{ route('posts.edit', $post->id) }}" class="flex items-center text-blue-500 hover:text-blue-600 transition" title="Edit">
                                <!-- SVG Icon for Edit -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="#0000FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="#0000FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-red-500 hover:text-red-600 transition" title="Delete">
                                    <!-- SVG Icon for Delete -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <!-- Post Content -->
                    <p class="text-gray-700">{{ $post->content }}</p>
                    @if($post->image_path)
                    <img src="{{ asset('storage/public/'.$post->image_path) }}" alt="Post Image" class="w-[300px] sm:w-full h-auto mt-4 rounded-lg border border-[#434028] cursor-pointer" onclick="openImageModal('{{ asset('storage/public/'.$post->image_path) }}')">
                    @endif

                        <!-- Image Modal -->
                        <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-70 hidden transition-opacity duration-500"></div>
                        <div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden transition-transform duration-500 transform scale-75">
                            <div class="bg-white rounded-lg shadow-lg p-4">
                                <img id="modalImage" name="image" src="" alt="Modal Image" class="rounded-lg">
                                <button onclick="closeImageModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">Close</button>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <button class="like-button flex items-center text-gray-500 transition" data-post-id="{{ $post->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="font-bold h-6 w-6 mr-2 heart-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                Like (<span class="like-count">{{ $post->likes()->count() }}</span >)
                            </button>
                            <button class="flex items-center text-gray-500 hover:text-blue-500 transition comment-button" data-post-id="{{ $post->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg> Comment (<span class="comment-count">{{ $post->comments()->count() }}</span>)
                            </button>
                            <button class="flex items-center text-gray-500 hover:text-yellow-500 transition" onclick="sharePost({{ $post->id }})">
                            <svg  class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6495 0.799565C18.4834 -0.72981 16.0093 0.081426 16.0093 1.99313V3.91272C12.2371 3.86807 9.65665 5.16473 7.9378 6.97554C6.10034 8.9113 5.34458 11.3314 5.02788 12.9862C4.86954 13.8135 5.41223 14.4138 5.98257 14.6211C6.52743 14.8191 7.25549 14.7343 7.74136 14.1789C9.12036 12.6027 11.7995 10.4028 16.0093 10.5464V13.0069C16.0093 14.9186 18.4834 15.7298 19.6495 14.2004L23.3933 9.29034C24.2022 8.2294 24.2022 6.7706 23.3933 5.70966L19.6495 0.799565ZM7.48201 11.6095C9.28721 10.0341 11.8785 8.55568 16.0093 8.55568H17.0207C17.5792 8.55568 18.0319 9.00103 18.0319 9.55037L18.0317 13.0069L21.7754 8.09678C22.0451 7.74313 22.0451 7.25687 21.7754 6.90322L18.0317 1.99313V4.90738C18.0317 5.4567 17.579 5.90201 17.0205 5.90201H16.0093C11.4593 5.90201 9.41596 8.33314 9.41596 8.33314C8.47524 9.32418 7.86984 10.502 7.48201 11.6095Z" fill="#0F0F0F"/>
                            <path d="M7 1.00391H4C2.34315 1.00391 1 2.34705 1 4.00391V20.0039C1 21.6608 2.34315 23.0039 4 23.0039H20C21.6569 23.0039 23 21.6608 23 20.0039V17.0039C23 16.4516 22.5523 16.0039 22 16.0039C21.4477 16.0039 21 16.4516 21 17.0039V20.0039C21 20.5562 20.5523 21.0039 20 21.0039H4C3.44772 21.0039 3 20.5562 3 20.0039V4.00391C3 3.45162 3.44772 3.00391 4 3.00391H7C7.55228 3.00391 8 2.55619 8 2.00391C8 1.45162 7.55228 1.00391 7 1.00391Z" fill="#0F0F0F"/>
                            </svg> Share
                            </button>
                        </div>
                        <div id="commentForm{{ $post->id }}" class="mt-4">
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="space-y-2" onsubmit="hideCommentForm({{ $post->id }});">
                                @csrf
                                <textarea name="content" placeholder="Tulis komentar di sini" class="w-full px-2 py-1 bg-gray-50 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]"></textarea>
                                <button type="submit" class="bg-[#6FA843] text-white px-4 py-2 rounded-lg hover:bg-[#578432] transition">Kirim Komentar</button>
                            </form>
                        </div>
                        <div class="comments mt-4 hidden" id="comments{{ $post->id }}">
                        @foreach($post->comments as $comment)
                        <div class="bg-gray-100 p-2 rounded-lg mb-2">
                            <strong class="p-2 my-2">{{ $comment->user->name }}</strong>:
                            @if(auth()->check() && (auth()->user()->id === $comment->user_id || auth()->user()->is_admin)) <!-- Check if user is the owner or an admin -->
                                <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="content" value="{{ $comment->content }}" class="w-full bg-gray-50 p-3 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]" required>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-lg hover:bg-blue-600 transition">Update</button>
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded-lg hover:bg-red-600 transition">Delete</button>
                                    </form>
                                </form>
                            @else
                                <span>{{ $comment->content }}</span>
                            @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>


                <!-- Likes Tab -->
                <div id="likes-tab" class="tab-content active:underline hidden bg-white rounded-b-xl shadow-md p-4">
                @foreach($likedPosts as $likedPost)
                <div class="bg-[#F7F0CF] shadow-lg p-6 rounded-3xl mt-6 mb-6 border border-gray-200">
                    <div class="flex items-center justify-right mb-4">
                    @if (isset($likedPost->user->profile_picture) && $likedPost->user->profile_picture)
                        <img src="{{ asset('storage/' . $likedPost->user->profile_picture) }}" alt="{{ $likedPost->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                    @else
                        <img src="{{ asset('images/avatar1.png') }}" alt="{{ $likedPost->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                    @endif
                        <div>
                            <h2 class="font-bold text-[#2D3748]">{{ $likedPost->user->name }}</h2>
                            <p class="text-gray-500 text-sm">{{ $likedPost->created_at->format('d M Y - H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Topic and Edit/Delete Buttons -->
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-bold text-xl text-[#6FA843]">Topik: {{ $likedPost->topic ?? 'Umum' }}</p>
                        @if(auth()->check() && (auth()->user()->id === $likedPost->user_id || auth()->user()->is_admin)) 
                        <div class="flex space-x-4">
                            <a href="{{ route('posts.edit', $likedPost->id) }}" class="flex items-center text-blue-500 hover:text-blue-600 transition" title="Edit">
                                <!-- SVG Icon for Edit -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="#0000FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="#0000FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <form action="{{ route('posts.destroy', $likedPost->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-red-500 hover:text-red-600 transition" title="Delete">
                                    <!-- SVG Icon for Delete -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <!-- Post Content -->
                    <p class="text-gray-700">{{ $likedPost->content }}</p>
                    @if($likedPost->image_path)
                    <img src="{{ asset('storage/public/'.$likedPost->image_path) }}" alt="Post Image" class="w-[300px] sm:w-full h-auto mt-4 rounded-lg border border-[#434028] cursor-pointer" onclick="openImageModal('{{ asset('storage/public/'.$likedPost->image_path) }}')">
                    @endif

                        <!-- Image Modal -->
                        <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-70 hidden transition-opacity duration-500"></div>
                        <div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden transition-transform duration-500 transform scale-75">
                            <div class="bg-white rounded-lg shadow-lg p-4">
                                <img id="modalImage" name="image" src="" alt="Modal Image" class="rounded-lg">
                                <button onclick="closeImageModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">Close</button>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <button class="like-button flex items-center text-gray-500 transition" data-post-id="{{ $likedPost->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="font-bold h-6 w-6 mr-2 heart-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                Like (<span class="like-count">{{ $likedPost->likes()->count() }}</span >)
                            </button>
                            <button class="flex items-center text-gray-500 hover:text-blue-500 transition comment-button" data-post-id="{{ $likedPost->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg> Comment (<span class="comment-count">{{ $likedPost->comments()->count() }}</span>)
                            </button>
                            <button class="flex items-center text-gray-500 hover:text-yellow-500 transition" onclick="sharePost({{ $likedPost->id }})">
                            <svg  class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.6495 0.799565C18.4834 -0.72981 16.0093 0.081426 16.0093 1.99313V3.91272C12.2371 3.86807 9.65665 5.16473 7.9378 6.97554C6.10034 8.9113 5.34458 11.3314 5.02788 12.9862C4.86954 13.8135 5.41223 14.4138 5.98257 14.6211C6.52743 14.8191 7.25549 14.7343 7.74136 14.1789C9.12036 12.6027 11.7995 10.4028 16.0093 10.5464V13.0069C16.0093 14.9186 18.4834 15.7298 19.6495 14.2004L23.3933 9.29034C24.2022 8.2294 24.2022 6.7706 23.3933 5.70966L19.6495 0.799565ZM7.48201 11.6095C9.28721 10.0341 11.8785 8.55568 16.0093 8.55568H17.0207C17.5792 8.55568 18.0319 9.00103 18.0319 9.55037L18.0317 13.0069L21.7754 8.09678C22.0451 7.74313 22.0451 7.25687 21.7754 6.90322L18.0317 1.99313V4.90738C18.0317 5.4567 17.579 5.90201 17.0205 5.90201H16.0093C11.4593 5.90201 9.41596 8.33314 9.41596 8.33314C8.47524 9.32418 7.86984 10.502 7.48201 11.6095Z" fill="#0F0F0F"/>
                            <path d="M7 1.00391H4C2.34315 1.00391 1 2.34705 1 4.00391V20.0039C1 21.6608 2.34315 23.0039 4 23.0039H20C21.6569 23.0039 23 21.6608 23 20.0039V17.0039C23 16.4516 22.5523 16.0039 22 16.0039C21.4477 16.0039 21 16.4516 21 17.0039V20.0039C21 20.5562 20.5523 21.0039 20 21.0039H4C3.44772 21.0039 3 20.5562 3 20.0039V4.00391C3 3.45162 3.44772 3.00391 4 3.00391H7C7.55228 3.00391 8 2.55619 8 2.00391C8 1.45162 7.55228 1.00391 7 1.00391Z" fill="#0F0F0F"/>
                            </svg> Share
                            </button>
                        </div>
                        <div id="commentForm{{ $likedPost->id }}" class="mt-4">
                            <form action="{{ route('posts.comment', $likedPost->id) }}" method="POST" class="space-y-2" onsubmit="hideCommentForm({{ $likedPost->id }});">
                                @csrf
                                <textarea name="content" placeholder="Tulis komentar di sini" class="w-full px-2 py-1 bg-gray-50 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]"></textarea>
                                <button type="submit" class="bg-[#6FA843] text-white px-4 py-2 rounded-lg hover:bg-[#578432] transition">Kirim Komentar</button>
                            </form>
                        </div>
                        <div class="comments mt-4 hidden" id="comments{{ $likedPost->id }}">
                        @foreach($likedPost->comments as $comment)
                        <div class="bg-gray-100 p-2 rounded-lg mb-2">
                            <strong class="p-2 my-2">{{ $comment->user->name }}</strong>:
                            @if(auth()->check() && (auth()->user()->id === $comment->user_id || auth()->user()->is_admin)) <!-- Check if user is the owner or an admin -->
                                <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="content" value="{{ $comment->content }}" class="w-full bg-gray-50 p-3 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]" required>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-lg hover:bg-blue-600 transition">Update</button>
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded-lg hover:bg-red-600 transition">Delete</button>
                                    </form>
                                </form>
                            @else
                                <span>{{ $comment->content }}</span>
                            @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
            </div>
        </div>

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
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', () => {
                    const tab = button.getAttribute('data-tab');
                    
                    document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
                    
                    button.classList.add('active');
                    document.getElementById(`${tab}-tab`).classList.remove('hidden');
                });
            });

            function openImageModal(imageSrc) { 
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                const backdrop = document.getElementById('modalBackdrop');
                
                modalImage.src = imageSrc;
                backdrop.classList.remove('hidden');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('scale-75');
                    modal.classList.add('scale-100');
                }, 10);
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                const backdrop = document.getElementById('modalBackdrop');
                modal.classList.add('scale-75');
                backdrop.classList.add('opacity-0');
            
                setTimeout(() => {
                    modal.classList.add('hidden');
                    backdrop.classList.add('hidden');
                }, 500); // Match the duration of the CSS transition
            }

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