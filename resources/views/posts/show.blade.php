<x-app-layout>
    <div class="py-6">
    
    @include('posts.partials.post-card')
    
    </div>
    
<script>
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

</script>
</x-app-layout>