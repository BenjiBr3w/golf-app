@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Search Results</h2>
        @if($users->isEmpty())
            <p>No users found.</p>
        @else
            <ul>
                @foreach($users as $user)
                    <li>
                        {{ $user->name }} ({{ $user->email }})
                        <form action="{{ route('friends.add', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-500">Add Friend</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
