@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl text-white font-bold mb-6">Friends' Posts</h1>

    @foreach ($posts as $post)
    <div class="post-container bg-white shadow rounded-lg p-6 mb-6">
        <!-- Post Header -->
        <h2 class="text-lg font-semibold">
            <a href="{{ route('user.show', $post->user->id) }}" class="text-blue-600 hover:underline">
                {{ $post->user->name }}
            </a>
            <span class="text-gray-500 text-sm">posted on {{ $post->created_at->format('d M Y, H:i') }}</span>
        </h2>

        <!-- Post Content -->
        <p class="mt-4 text-gray-700">{{ $post->content }}</p>

        <!-- Post Image (Optional) -->
        @if ($post->image)
        <div class="mt-4">
            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full rounded-lg">
        </div>
        @endif

        <!-- Comments Section -->
        <div class="mt-6">
            <h3 class="text-md font-semibold">Comments</h3>
            <ul class="comments-list mt-2 space-y-4">
                @foreach ($post->comments as $comment)
                <li class="mb-4 bg-gray-100 p-3 rounded-lg">
                    <a href="{{ route('user.show', $comment->user->id) }}" class="text-blue-600 hover:underline">
                        <strong>{{ $comment->user->name }}</strong>
                    </a>
                    <span class="text-gray-500 text-sm">said on {{ $comment->created_at->format('d M Y, H:i') }}</span>
                    <p>{{ $comment->content }}</p>
                </li>
                @endforeach
            </ul>
        </div>

        @if ($post->user_id === auth()->id())
            <a href="{{ route('posts.edit', $post->id) }}" 
                class="bg-yellow-500 hover:bg-yellow-700 text-gray font-bold py-1 px-4 rounded mt-2 inline-block">
                Edit
            </a>
        @endif

        @if ($post->user_id === auth()->id())
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="bg-red-500 hover:bg-red-700 text-gray font-bold py-1 px-4 rounded mt-2">
                    Delete
                </button>
            </form>
        @endif

        <!-- Add a Comment -->
        <form 
            id="comment-form-{{ $post->id }}"
            class="comment-form mt-4" 
            data-post-id="{{ $post->id }}"
            method="POST"
            action="{{ route('posts.comments.store', $post->id) }}"
        >
            @csrf
            <textarea
                name="content"
                rows="2"
                class="w-full p-2 border rounded comment-input"
                placeholder="Write a comment..."
                required
            ></textarea>
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-grey font-bold py-2 px-4 rounded mt-2"
            >
                Add Comment
            </button>
        </form>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    // Handle comment form submission via JavaScript for AJAX functionality
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const postId = this.dataset.postId;

            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Server responded with an error');
            })
            .then(data => {
                if (data.success) {
                    const commentsList = this.closest('.post-container').querySelector('.comments-list');
                    const newComment = `
                        <li class="mb-4 bg-gray-100 p-3 rounded-lg">
                            <a href="/users/${data.comment.user.id}" class="text-blue-600 hover:underline">
                                <strong>${data.comment.user.name}</strong>
                            </a>
                            <span class="text-gray-500 text-sm">said just now</span>
                            <p>${data.comment.content}</p>
                        </li>
                    `;
                    commentsList.insertAdjacentHTML('beforeend', newComment);
                    this.reset();
                } else {
                    alert(data.message || 'Failed to add comment.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    });
</script>
@endsection





