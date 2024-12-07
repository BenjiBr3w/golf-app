@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Comment</h2>

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <textarea 
            name="content" 
            rows="3" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            required>{{ old('content', $comment->content) }}</textarea>

        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg mt-4">
            Save Changes
        </button>
    </form>
</div>
@endsection
