@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Create a New Post</h2>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Caption -->
        <div class="mb-4">
            <label for="caption" class="block text-gray-700 font-bold mb-2">Caption</label>
            <textarea 
                id="caption" 
                name="caption" 
                rows="3" 
                class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" 
                required>{{ old('caption') }}</textarea>
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-bold mb-2">Image (Optional)</label>
            <input 
                type="file" 
                id="image" 
                name="image" 
                accept="image/*" 
                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            Create Post
        </button>
    </form>
</div>
@endsection
