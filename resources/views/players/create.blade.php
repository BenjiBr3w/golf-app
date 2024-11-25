@extends('layouts.app')

@section('title', 'Add a Player')

@section('content')
    <form method="POST" action=" {{ route('players.store') }}">
        @csrf
        <p>Name: <input type="text" name="name"
            value="{{ old('name') }}"></p>
        <p>Handicap: <input type="number" name="handicap"
            value="{{ old('handicap') }}" step="0.1"></p>
        <input type="submit" value="Submit">
        <a href="{{ route('players.index') }}">Cancel</a>
    </form>

@endsection