@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Log a New Round</h2>
    <form action="{{ route('scores.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="course_name" class="block text-gray-700 font-bold">Course Name</label>
            <input type="text" id="course_name" name="course_name" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="score" class="block text-gray-700 font-bold">Score</label>
            <input type="number" id="score" name="score" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="date" class="block text-gray-700 font-bold">Date</label>
            <input type="date" id="date" name="date" class="w-full p-2 border rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
            Record Round
        </button>
    </form>
</div>
@endsection
