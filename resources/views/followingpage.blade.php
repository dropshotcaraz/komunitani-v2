<x-app-layout>
    <div class="justify-center py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Post Feed -->
            @if ($posts->isEmpty()) <!-- Cek apakah tidak ada post -->
                <div class="text-center text-gray-500">
                    <p>{{ __('No posts available from the users you follow.') }}</p>
                    <p>{{ __('You can follow users to see their posts.') }}</p>
                </div>
            @else
                @foreach ($posts as $post)
                    @include('posts.partials.post-card')
                @endforeach
            @endif
            @include('posts.partials.back-to-top')
        </div>

        <script>
            function hideCommentForm(postId) {
                document.getElementById('commentForm' + postId).classList.add('hidden');
            }

            document.querySelectorAll('.like-button').forEach(button => {
                const postId = button.getAttribute('data-post-id');

                const likedState = localStorage.getItem(`liked_${postId}`);
                const likeCountElement = button.querySelector('.like-count');
                const heartIcon = button.querySelector('.heart-icon');

                if (likedState === 'true' || likedState === true) {
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
                                localStorage.setItem(`liked_${postId}`, data.liked);
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
</x-app-layout>
