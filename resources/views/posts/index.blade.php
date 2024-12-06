@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Friends' Posts</h1>

    @forelse ($posts as $post)
        <div class="post-container bg-white shadow-md rounded-lg p-6 mb-6">
            <!-- Post Header -->
            <h2 class="text-xl font-semibold text-gray-800">
                <a href="{{ route('user.show', $post->user->id) }}" class="text-blue-600 hover:underline">
                    {{ $post->user->name }}
                </a>
                <span class="text-sm text-gray-500 block mt-1">posted on {{ $post->created_at->format('d M Y, H:i') }}</span>
            </h2>

            <!-- Post Content -->
            <p class="mt-4 text-gray-700">{{ $post->content }}</p>

            <!-- Post Image (Optional) -->
            @if ($post->image)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full rounded-lg shadow">
                </div>
            @endif

            <!-- Comments Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800">Comments</h3>
                <ul class="comments-list mt-4 space-y-4">
                    @foreach ($post->comments as $comment)
                        <li class="mb-4 bg-gray-100 p-4 rounded-lg shadow">
                            <a href="{{ route('user.show', $comment->user->id) }}" class="text-blue-600 hover:underline font-semibold">
                                {{ $comment->user->name }}
                            </a>
                            <span class="text-sm text-gray-500 block mt-1">said on {{ $comment->created_at->format('d M Y, H:i') }}</span>
                            <p class="mt-2 text-gray-700">{{ $comment->content }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Post Actions (Edit & Delete for Owner) -->
            @if ($post->user_id === auth()->id())
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('posts.edit', $post->id) }}" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-gray font-bold py-2 px-4 rounded-lg transition">
                        Edit
                    </a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-gray font-bold py-2 px-4 rounded-lg transition">
                            Delete
                        </button>
                    </form>
                </div>
            @endif

            <!-- Add a Comment -->
            <form 
                id="comment-form-{{ $post->id }}"
                class="comment-form mt-6"
                data-post-id="{{ $post->id }}"
                method="POST"
                action="{{ route('posts.comments.store', $post->id) }}"
            >
                @csrf
                <textarea
                    name="content"
                    rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Write a comment..."
                    required
                ></textarea>
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-gray font-bold py-2 px-4 rounded-lg mt-2 transition">
                    Add Comment
                </button>
            </form>
        </div>
    @empty
        <p class="text-gray-500 text-center">No posts available. Start following your friends to see their posts!</p>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
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
                if (!response.ok) throw new Error('Failed to add comment.');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const commentsList = this.closest('.post-container').querySelector('.comments-list');
                    const newComment = `
                        <li class="mb-4 bg-gray-100 p-4 rounded-lg shadow">
                            <a href="/users/${data.comment.user.id}" class="text-blue-600 hover:underline font-semibold">
                                ${data.comment.user.name}
                            </a>
                            <span class="text-sm text-gray-500 block mt-1">said just now</span>
                            <p class="mt-2 text-gray-700">${data.comment.content}</p>
                        </li>
                    `;
                    commentsList.insertAdjacentHTML('beforeend', newComment);
                    this.reset();
                } else {
                    alert(data.message || 'Failed to add comment.');
                }
            })
            .catch(error => console.error(error));
        });
    });
</script>
@endsection






