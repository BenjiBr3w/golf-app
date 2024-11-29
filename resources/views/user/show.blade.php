@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl text-white font-bold mb-6">{{ $user->name }}'s Posts and Comments</h1>

    <!-- Display User's Posts -->
    @foreach ($posts as $post)
    <div class="post-container bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold">Posted on {{ $post->created_at->format('d M Y, H:i') }}</h2>
        <p class="mt-4 text-gray-700">{{ $post->content }}</p>

        @if ($post->image_link)
        <div class="mt-4">
            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full rounded-lg">
        </div>
        @endif

        <!-- Display Comments -->
        <div class="mt-6">
            <h3 class="text-md font-semibold">Comments</h3>
            <ul class="comments-list mt-2 space-y-4">
                @foreach ($post->comments as $comment)
                <li class="mb-4 bg-gray-100 p-3 rounded-lg">
                    <strong>{{ $comment->user->name }}</strong>
                    <p>{{ $comment->content }}</p>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
@endsection
