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

            <!-- Post Actions (Edit & Delete for Post Owner) -->
            @if ($post->user_id === auth()->id())
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('posts.edit', $post->id) }}" 
                        class="bg-yellow-500 text-gray-700 hover:bg-yellow-600 py-2 px-4 rounded-lg font-bold transition">
                        Edit Post
                    </a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="bg-red-500 text-gray-700 hover:bg-red-600 py-2 px-4 rounded-lg font-bold transition">
                            Delete Post
                        </button>
                    </form>
                </div>
            @endif

            <!-- Comments Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800">Comments</h3>
                <ul class="comments-list mt-4 space-y-4">
                    @foreach ($post->comments as $comment)
                        <li class="mb-4 bg-gray-100 p-4 rounded-lg shadow" id="comment-{{ $comment->id }}">
                            <div class="flex justify-between">
                                <div>
                                    <a href="{{ route('user.show', $comment->user->id) }}" class="text-blue-600 hover:underline font-semibold">
                                        {{ $comment->user->name }}
                                    </a>
                                    <span class="text-sm text-gray-500 block mt-1">said on {{ $comment->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                @if ($comment->user_id === auth()->id())
                                    <div>
                                        <button class="edit-button bg-yellow-500 text-gray-700 py-1 px-3 rounded hover:bg-yellow-600 transition" onclick="showEditForm({{ $comment->id }})">
                                            Edit
                                        </button>
                                        <button class="delete-button bg-red-500 text-gray-700 py-1 px-3 rounded hover:bg-red-600 transition" onclick="deleteComment({{ $comment->id }})">
                                            Delete
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <p class="mt-2 text-gray-700" id="comment-content-{{ $comment->id }}">{{ $comment->content }}</p>
                            
                            <!-- Hidden Edit Form -->
                            <form id="edit-comment-form-{{ $comment->id }}" class="hidden mt-2" onsubmit="event.preventDefault(); updateComment({{ $comment->id }});">
                                <textarea class="w-full border rounded-lg p-2" id="edit-comment-content-{{ $comment->id }}">{{ $comment->content }}</textarea>
                                <button class="bg-blue-500 text-gray-700 py-1 px-3 rounded hover:bg-blue-600 transition mt-2">
                                    Save
                                </button>
                                <button type="button" class="bg-gray-500 text-gray-700 py-1 px-3 rounded hover:bg-gray-600 transition mt-2" onclick="cancelEdit({{ $comment->id }})">
                                    Cancel
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>

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
                    class="bg-blue-500 hover:bg-blue-600 text-gray-700 font-bold py-2 px-4 rounded-lg mt-2 transition">
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
    function showEditForm(commentId) {
        document.getElementById(`comment-content-${commentId}`).classList.add('hidden');
        document.getElementById(`edit-comment-form-${commentId}`).classList.remove('hidden');
    }

    function cancelEdit(commentId) {
        document.getElementById(`edit-comment-form-${commentId}`).classList.add('hidden');
        document.getElementById(`comment-content-${commentId}`).classList.remove('hidden');
    }

    function updateComment(commentId) {
        const content = document.getElementById(`edit-comment-content-${commentId}`).value;

        fetch(`/comments/${commentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ content }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`comment-content-${commentId}`).textContent = content;
                cancelEdit(commentId);
            } else {
                alert(data.message || 'Failed to update comment.');
            }
        })
        .catch(error => console.error('Error updating comment:', error));
    }

    function deleteComment(commentId) {
        if (!confirm('Are you sure you want to delete this comment?')) return;

        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentElement = document.getElementById(`comment-${commentId}`);
                commentElement.remove();
            } else {
                alert(data.message || 'Failed to delete comment.');
            }
        })
        .catch(error => console.error('Error deleting comment:', error));
    }

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
                        <li class="mb-4 bg-gray-100 p-4 rounded-lg shadow" id="comment-${data.comment.id}">
                            <div class="flex justify-between">
                                <div>
                                    <a href="/users/${data.comment.user.id}" class="text-blue-600 hover:underline font-semibold">
                                        ${data.comment.user.name}
                                    </a>
                                    <span class="text-sm text-gray-500 block mt-1">said just now</span>
                                </div>
                                <div>
                                    <button class="edit-button bg-yellow-500 text-gray-700 py-1 px-3 rounded hover:bg-yellow-600 transition" onclick="showEditForm(${data.comment.id})">
                                        Edit
                                    </button>
                                    <button class="delete-button bg-red-500 text-gray-700 py-1 px-3 rounded hover:bg-red-600 transition" onclick="deleteComment(${data.comment.id})">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700" id="comment-content-${data.comment.id}">${data.comment.content}</p>
                        </li>
                    `;
                    commentsList.insertAdjacentHTML('beforeend', newComment);
                    addCommentEventListeners(data.comment.id);
                    this.reset();
                } else {
                    alert(data.message || 'Failed to add comment.');
                }
            })
            .catch(error => console.error('Error adding comment:', error));
        });
    });

    function addCommentEventListeners(commentId) {
        const editButton = document.querySelector(`#comment-${commentId} .edit-button`);
        editButton?.addEventListener('click', () => showEditForm(commentId));

        const deleteButton = document.querySelector(`#comment-${commentId} .delete-button`);
        deleteButton?.addEventListener('click', () => deleteComment(commentId));
    }
</script>
@endsection











