@extends('layouts.app')

@section('content')
<div class="mt-8">
    <h3 class="text-xl font-semibold text-gray-800">Search Results</h3>
        <ul>
        @foreach ($users as $user)
            <li class="flex justify-between items-center mt-2">
                <!-- User name styled with custom Tailwind classes -->
                <span class="user-name text-blue-500 font-semibold hover:text-blue-700">
                    {{ $user->name }}
                </span>
                <!-- Add Friend button also styled with Tailwind classes -->
                <button
                    class="add-friend-button bg-green-500 hover:bg-green-700 text-white py-1 px-4 rounded-lg"
                    data-friend-id="{{ $user->id }}"
                >
                    Add Friend
                </button>
            </li>
        @endforeach
    </ul>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.add-friend-button').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            let friendId = this.dataset.friendId;
            fetch('/friends/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ friend_id: friendId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Friend added successfully!');
                } else {
                    alert(data.message);
                }
            });
        });
    });
</script>
@endsection
