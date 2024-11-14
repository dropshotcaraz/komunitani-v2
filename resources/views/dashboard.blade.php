<x-app-layout>
    <div class="flex min-h-screen">
        <!-- Sidebar (if you're using one) -->
        <aside class="w-64 bg-gray-800 text-white hidden md:block">
            <!-- Sidebar content goes here -->
        </aside>
        
        <!-- Main Content -->
        <div class="flex-grow p-6">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <main class="w-full">
                    <!-- Post Bar -->
                    <div class="bg-white shadow-lg p-6 rounded-3xl mb-10 border border-gray-200">
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
                            <button type="submit" id="submitPost" class="w-full mt-2 bg-[#618805] text-black px-4 py-2 rounded-lg hover:bg-[#578432] transition">Kirim</button>
                        </form>
                    </div>

                    <!-- Post Feed -->
                <div id="postFeed">
                @foreach($posts as $post)
                <div class="bg-[#ffffff] shadow-lg p-6 rounded-3xl mt-6 mb-6 border border-gray-200">
                    <div class="flex items-center justify-right mb-4">
                    @if (isset($post->user->profile_picture) && $post->user->profile_picture)
                        <img src="{{ asset('storage/' . $post->user->profile_picture) }}" alt="{{ $post->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                    @else
                        <img src="{{ asset('images/avatar1.png') }}" alt="{{ $post->user->name }}" class="w-[50px] h-12 rounded-full mr-4 border border-gray-300">
                    @endif
                        <div>
                            <h2 class="font-bold text-[#2D3748]">{{ $post->user->name }}</h2>
                            <p class="text-gray-500 text-sm">{{ $post->created_at->setTimezone('Asia/Jakarta')->format('d M Y - H:i') }}</p>
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
                    modal.removeClass('scale-100').addClass('scale-75');
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
                    const likedState = localStorage.getItem(liked_${postId});
                    const likeCountElement = button.querySelector('.like-count');
                    const heartIcon = button.querySelector('.heart-icon');

                    // Set initial like count and color based on local storage
                    if (likedState === 'true' || likedState === true) {
                        heartIcon.classList.add('text-red-500');
                        button.classList.add('text-red-500');
                    }

                    button.addEventListener('click', function() {
                        fetch(/posts/${postId}/like, {
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
                                localStorage.setItem(liked_${postId}, data .liked);
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
