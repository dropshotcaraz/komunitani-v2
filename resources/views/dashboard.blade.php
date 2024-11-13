<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div class="justify-center py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <main class="col-span-12 md:col-span-9 p-4 mt-20 sm:mt-0 md:mt-0 w-auto">

                <!-- Post Bar -->
                <div class="bg-[#6FA843] shadow-lg p-6 rounded-3xl mb-10 border border-gray-200">
                    <h1 class="font-bold text-white text-3xl mb-4">Beranda Post</h1>
                    
                    <form id="postForm" class="space-y-4" action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea id="postTextarea" name="content" placeholder="Tulis pertanyaan atau informasi di sini" class="w-full bg-gray-50 p-3 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]"></textarea>
                        <div class="flex items-center space-x-3">
                            <select id="topicSelect" name="topic" class="border border-gray-300 py-2 px-8 rounded-lg bg-gray-50 focus:border-[#6FA843]">
                                <option value="">Pilih Topik</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Kesehatan">Kesehatan</option>
                            </select>
                            <button type="button" onclick="document.getElementById('imageUpload').click()" class="flex items-center text-gray-500 hover:text-[#6FA843] transition">
                                <svg class="bg-white rounded-xl" width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25H12.0574C14.3658 1.24999 16.1748 1.24998 17.5863 1.43975C19.031 1.63399 20.1711 2.03933 21.0659 2.93414C21.9607 3.82895 22.366 4.96897 22.5603 6.41371C22.75 7.82519 22.75 9.63423 22.75 11.9426V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM6.61358 2.92637C5.33517  3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12C21.25 9.62178 21.2484 7.91356 21.0736 6.61358C20.9018 5.33517 20.5749 4.56445 20.0052 3.9948C19.4355 3.42514 18.6648 3.09825 17.3864 2.92637C16.0864 2.75159 14.3782 2.75 12 2.75C9.62178 2.75 7.91356 2.75159 6.61358 2.92637ZM12 8.75C10.2051 8.75 8.75 10.2051 8.75 12C8.75 13.7949 10.2051 15.25 12 15.25C13.7949 15.25 15.25 13.7949 15.25 12C15.25 10.2051 13.7949 8.75 12 8.75ZM7.25 12C7.25 9.37665 9.37665 7.25 12 7.25C14.6234 7.25 16.75 9.37665 16.75 12C16.75 14.6234 14.6234 16.75 12 16.75C9.37665 16.75 7.25 14.6234 7.25 12Z" fill="#1C274C"/>
                                </svg>
                            <input type="file" id="imageUpload" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>

                        <!-- Thumbnail Preview -->
                        <div id="thumbnailPreview" class="mt-4">
                            <img id="thumbnail" src="" alt="Image Preview" class="hidden w-full h-auto rounded-lg border border-gray-200">
                        </div>
                        <button type="submit" id="submitPost" class="w-full mt-2 bg-[#F7F0CF] text-black px-4 py-2 rounded-lg hover:bg-[#578432] transition">Kirim</button>
                    
                    </form>
                </div>

                <!-- Post Feed -->
                <div id="postFeed">
                    @foreach($posts as $post)
                    <div class="bg-[#F7F0CF] shadow-lg p-6 rounded-3xl mt-6 mb-6 border border-gray-200">
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('images/avatar1.png') }}" alt="{{ $post->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                            <div>
                                <h2 class="font-bold text-[#2D3748]">{{ $post->user->name }}</h2>
                                <p class="text-gray-500 text-sm">{{ $post->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                        <p class="font-semibold text-[#6FA843] mb-1">Topik: {{ $post->topic ?? 'Umum' }}</p>
                        <p class="text-gray-700">{{ $post->content }}</p>
                        @if($post->image_path)
                        <img src="{{ asset("storage/public/{$post->image_path}") }}" alt="Post Image" class="w-[300px] sm:w-full h-auto mt-4 rounded-lg border border-gray-200 cursor-pointer" onclick="openImageModal('{{ asset("storage/public/{$post->image_path}") }}')">
                        @endif
                        
                        <!-- Edit and Delete Buttons -->
                        <div class="flex justify-end mt-4">
                            @if(auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->is_admin)) <!-- Check if user is the owner or an admin -->
                                <a href="{{ route('posts.edit', $post->id) }}" class="flex items-center text-blue-500 hover:text-blue-600 transition mr-4" title="Edit">
                                    <!-- SVG Icon for Edit -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h4m-4 0V3m0 4L3 21h18l-4-4m-4-4l4-4" />
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
                            @endif
                        </div>

                        <!-- Image Modal -->
                        <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-70 hidden transition-opacity duration-500"></div>
                        <div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden transition-transform duration-500 transform scale-50">
                            <div class="bg-white rounded-lg shadow-lg p-4">
                                <img id="modalImage" name="image" src="" alt="Modal Image" class="rounded-lg">
                                <button onclick="closeImageModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">Close</button>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <button class="like-button flex items-center text-gray-500 transition" data-post-id="{{ $post->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 heart-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-4.55a2 2 0 00-2.83-2.83L12 7 .17 8.28 3.45a2 2 0 00-2.83 2.83L10 10m5 10v-6a2 2 0 00-2-2H7a2 2 0 00-2 2v6"/>
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

                <script>
                    function confirmDelete(url) {
                    if (confirm('Are you sure you want to delete this comment?')) {
                        window.location.href = url;
                    }
                }

                function hideCommentForm(postId) {
                    // Optionally hide the comment form after submission
                    document.getElementById('commentForm' + postId).classList.add('hidden');
                    // Optionally show the comments section
                    document.getElementById('comments' + postId).classList.remove('hidden');
                }
                function openImageModal(imageSrc) { 
                    const modal = $('#imageModal');
                    const modalImage = $('#modalImage');
                    const backdrop = $('#modalBackdrop');
                    
                    modalImage.attr('src', imageSrc);
                    
                    // Show the backdrop and modal with Tailwind CSS classes
                    backdrop.removeClass('hidden').addClass('flex opacity-100');
                    modal.removeClass('hidden').addClass('scale-80');
                }

                function closeImageModal() {
                    const modal = $('#imageModal');
                    const backdrop = $('#modalBackdrop');
                    
                    // Remove classes to trigger Tailwind CSS transitions
                    modal.removeClass('scale-100').addClass('scale-50');
                    backdrop.removeClass('opacity-100').addClass('opacity-0');

                    // Hide modal and backdrop after transition
                    setTimeout(() => {
                        modal.addClass('hidden'); // Hide modal after transition
                        backdrop.addClass('hidden'); // Hide backdrop after transition
                    }, 500); // Match the duration of the CSS transition
                }

                function hideCommentForm(postId) {
                    document.getElementById('commentForm' + postId).classList.add('hidden');
                }

                document.querySelectorAll('.like-button').forEach(button => {
                    const postId = button.getAttribute('data-post-id');

                    // Check local storage for liked state
                    const likedState = localStorage.getItem(`liked_${postId}`);
                    const likeCountElement = button.querySelector('.like-count');
                    const heartIcon = button.querySelector('.heart-icon');

                    // Set initial like count and color based on local storage
                    if (likedState === 'true') {
                        heartIcon.classList.add('text-red-500');
                        button.classList.add('text-red-500');
                    }

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

                                // Store the liked state in local storage
                                localStorage.setItem(`liked_${postId}`, data .liked);
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

                function previewImage(event) {
                    const file = event.target.files[0];
                    const thumbnail = document.getElementById('thumbnail');
                    const thumbnailPreview = document.getElementById('thumbnailPreview');

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            thumbnail.src = e.target.result;
                            thumbnail.classList.remove('hidden'); // Show the thumbnail
                        }
                        reader.readAsDataURL(file);
                    } else {
                        thumbnail.classList.add('hidden'); // Hide if no file
                    }
                }
                </script>
            </main>
        </div>
    </div>
</x-app-layout>