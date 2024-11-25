@extends('layouts.app')

@section('title', 'Players')

@section('content')
    <a href="{{ route('players.create') }}">Add Player</a>
    <p> All players registered with Golf App! </p>
    <ul>
        @foreach ($players as $player)
        <li><a href="{{ route('players.show', ['id' => $player->id]) }}">{{ $player->name }}</a></li>
        @endforeach
    </ul>
@endsection

