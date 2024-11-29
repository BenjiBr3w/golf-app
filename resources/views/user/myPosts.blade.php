@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">My Posts</h1>

    @foreach ($userPosts as $post)
    <div class="post-container bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold">
            {{ $post->created_at->format('d M Y, H:i') }}
        </h2>
        <p class="mt-4 text-gray-700">{{ $post->content }}</p>
        @if ($post->image)
        <div class="mt-4">
            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full rounded-lg">
        </div>
        @endif
    </div>
    @endforeach

    {{ $userPosts->links() }}
</div>
@endsection
