@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Friends' Rounds</h2>
    @foreach ($friendsRounds as $round)
        <div class="p-4 bg-gray-100 rounded-lg mb-4 shadow">
            <p><strong>{{ $round->user->name }}</strong> played at {{ $round->course_name }}</p>
            <p>Score: {{ $round->score }}</p>
            <p>Date: {{ $round->date->format('d M Y') }}</p>
        </div>
    @endforeach
</div>
@endsection
