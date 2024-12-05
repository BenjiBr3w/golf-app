@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Post</h2>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Post Content -->
        <div class="mb-4">
            <label for="content" class="block text-gray-700 font-bold mb-2">Content</label>
            <textarea 
                id="content" 
                name="content" 
                rows="3" 
                class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" 
                required>{{ old('content', $post->content) }}</textarea>
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-bold mb-2">Image (Optional)</label>
            <input 
                type="file" 
                id="image" 
                name="image" 
                accept="image/*" 
                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300"
            >
            @if ($post->image)
                <p class="text-sm text-gray-500 mt-2">Current image:</p>
                <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-32 h-32 rounded-lg mt-2">
            @endif
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-700 text-gray font-bold py-2 px-4 rounded-lg">
            Update Post
        </button>
    </form>
</div>
@endsection
