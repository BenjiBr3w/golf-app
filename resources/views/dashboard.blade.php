@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- My Posts -->
        <a href="{{ route('posts.myPosts') }}" class="bg-indigo-500 text-white rounded-lg shadow-lg p-6 hover:bg-indigo-600 transition transform hover:-translate-y-1">
            <h3 class="text-lg font-semibold">My Posts</h3>
            <p class="text-4xl font-extrabold">{{ $totalPosts }}</p>
        </a>

        <!-- Friends -->
        <div 
            class="bg-green-500 text-white rounded-lg shadow-lg p-6 cursor-pointer hover:bg-green-600 transition transform hover:-translate-y-1"
            id="friends-box"
            onclick="showFriendsList()"
        >
            <h3 class="text-lg font-semibold">Friends</h3>
            <p class="text-4xl font-extrabold" id="friend-count">{{ $friendCount }}</p>
        </div>

        <!-- Notifications -->
        <a href="#notifications" class="bg-yellow-500 text-white rounded-lg shadow-lg p-6 hover:bg-yellow-600 transition transform hover:-translate-y-1">
            <h3 class="text-lg font-semibold">Notifications</h3>
            <p class="text-4xl font-extrabold">{{ $notifications->count() }}</p>
        </a>
    </div>

    <!-- Leaderboard Summary -->
    <a href="{{ route('leaderboard.index') }}" class="block bg-white shadow-lg rounded-lg p-6 mb-8 hover:bg-gray-100 transition">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Leaderboard Summary</h2>
        <ul class="space-y-4">
            @foreach ($leaderboardSummary as $player)
                <li class="flex justify-between items-center">
                    <span class="text-gray-800 font-medium">{{ $player->name }}</span>
                    <span class="text-green-500 font-extrabold text-lg">{{ $player->best_score }}</span>
                </li>
            @endforeach
        </ul>
        <p class="text-blue-500 text-sm font-semibold mt-4">View Full Leaderboard &rarr;</p>
    </a>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Rounds -->
        <div class="bg-blue-500 text-white rounded-lg shadow-lg p-6 hover:bg-blue-600 transition transform hover:-translate-y-1">
            <h3 class="text-lg font-semibold">Total Rounds</h3>
            <p class="text-4xl font-extrabold">{{ $totalRounds }}</p>
        </div>

        <!-- Best Score -->
        <div class="bg-red-500 text-white rounded-lg shadow-lg p-6 hover:bg-red-600 transition transform hover:-translate-y-1">
            <h3 class="text-lg font-semibold">Best Score</h3>
            <p class="text-4xl font-extrabold">{{ $bestScore }}</p>
        </div>

        <!-- Unique Courses -->
        <div class="bg-purple-500 text-white rounded-lg shadow-lg p-6 hover:bg-purple-600 transition transform hover:-translate-y-1">
            <h3 class="text-lg font-semibold">Courses Played</h3>
            <p class="text-4xl font-extrabold">{{ $uniqueCourses }}</p>
        </div>
    </div>

    <!-- Search for Users to Add -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Search and Add Friends</h2>
        <form action="{{ route('friends.search') }}" method="POST" class="flex items-center space-x-4">
            @csrf
            <input 
                type="text" 
                name="search" 
                placeholder="Search for a user..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            >
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg"
            >
                Search
            </button>
        </form>
    </div>

    <!-- Search Results -->
    @if(isset($searchResults) && count($searchResults) > 0)
        <div class="bg-gray-100 shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Search Results</h2>
            <ul class="space-y-4">
                @foreach ($searchResults as $user)
                    <li class="flex justify-between items-center p-4 bg-gray-200 rounded-lg shadow">
                        <span class="text-gray-800 font-medium">{{ $user->name }}</span>
                        <button 
                            class="add-friend-button bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg"
                            data-friend-id="{{ $user->id }}"
                        >
                            Add Friend
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif(isset($searchResults))
        <div class="bg-gray-100 shadow-lg rounded-lg p-6">
            <p class="text-gray-500">No users found matching your search.</p>
        </div>
    @endif

    <!-- Friends Modal -->
    <div id="friends-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Your Friends</h3>
            <ul id="friends-list" class="space-y-2">
                <!-- Populated dynamically via JavaScript -->
            </ul>
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4" onclick="hideFriendsList()">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showFriendsList() {
        fetch('{{ route('api.friends') }}')
            .then(response => response.json())
            .then(friends => {
                const friendsList = document.getElementById('friends-list');
                friendsList.innerHTML = friends.length 
                    ? friends.map(friend => `<li class="text-gray-700 p-2 bg-gray-100 rounded">${friend.name}</li>`).join('')
                    : '<li class="text-gray-500 p-2 bg-gray-100 rounded">No friends found.</li>';
                document.getElementById('friends-modal').classList.remove('hidden');
            })
            .catch(() => alert('Could not load friends list. Try again later.'));
    }

    function hideFriendsList() {
        document.getElementById('friends-modal').classList.add('hidden');
    }
</script>
@endsection


