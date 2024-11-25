@extends('layouts.app')

@section('title', 'Player Details')

@section('content')
    <ul>
        <li>Name: {{ $player->name }}</li>
        <li>Handicap: {{ $player->handicap ?? 'Unknown' }}</li>
    </ul>

    <form method="POST"
        action="{{ route('players.destroy', ['id' => $player->id]) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>

    <p><a href="{{ route('players.index') }}">Back</a></p>
@endsection

