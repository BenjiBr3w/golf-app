@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Account Hub</h2>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Posts Stats -->
            <a href="{{ route('posts.myPosts') }}" class="bg-indigo-500 text-gray-100 rounded-lg shadow p-6 hover:bg-indigo-600 transition">
                <h3 class="text-lg font-semibold">My Posts</h3>
                <p class="text-3xl font-bold">{{ $totalPosts }}</p>
            </a>

            <!-- Friends Stats -->
            <div 
                class="bg-green-500 text-gray-100 rounded-lg shadow p-6 cursor-pointer hover:bg-green-600 transition" 
                id="friends-box"
                onclick="showFriendsList()"
            >
                <h3 class="text-lg font-semibold">Friends</h3>
                <p class="text-3xl font-bold" id="friend-count">{{ $friendCount }}</p>
            </div>

            <!-- Notifications -->
            <a href="#notifications" class="bg-yellow-500 text-gray-100 rounded-lg shadow p-6 hover:bg-yellow-600 transition">
                <h3 class="text-lg font-semibold">Notifications</h3>
                <p class="text-3xl font-bold">{{ $notifications->count() }}</p>
            </a>
        </div>

        <!-- Notifications Section -->
        <div id="notifications" class="bg-white shadow rounded-lg p-6 mt-8">
            <h2 class="text-lg font-bold mb-4">Notifications</h2>
            <ul class="list-disc pl-5">
                @forelse ($notifications as $notification)
                    <li class="mb-2">
                        <strong>{{ $notification->data['commenter_name'] }}</strong> commented on your post: 
                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="text-blue-500 underline">
                            {{ Str::limit($notification->data['content'], 50) }}
                        </a>
                        <span class="text-sm text-gray-500 block">{{ $notification->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">No notifications yet.</li>
                @endforelse
            </ul>
        </div>

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

        <!-- Add Friend Section -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-800">Add a Friend</h3>
            <form action="{{ route('friends.search') }}" method="POST" class="mt-4">
                @csrf
                <div class="flex items-center space-x-4">
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
                </div>
            </form>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-500 text-gray p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Total Rounds</h3>
                <p class="text-3xl font-bold">{{ $totalRounds }}</p>
            </div>
            <div class="bg-green-500 text-gray p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Best Score</h3>
                <p class="text-3xl font-bold">{{ $bestScore }}</p>
            </div>
            <div class="bg-yellow-500 text-gray p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Courses Played</h3>
                <p class="text-3xl font-bold">{{ $uniqueCourses }}</p>
            </div>
        </div>

        <!-- Search Results -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-800">Search Results</h3>
            <ul id="search-results" class="space-y-2 mt-4">
                @foreach ($searchResults ?? [] as $user)
                    <li class="flex justify-between items-center p-2 bg-gray-100 rounded">
                        <span class="text-gray-800">{{ $user->name }}</span>
                        <button 
                            class="add-friend-button bg-green-500 hover:bg-green-700 text-white font-semibold py-1 px-4 rounded-lg"
                            data-friend-id="{{ $user->id }}"
                        >
                            Add Friend
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Fetch friends and display in the modal
    function showFriendsList() {
        fetch('{{ route('api.friends') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch friends');
                }
                return response.json();
            })
            .then(friends => {
                const friendsList = document.getElementById('friends-list');
                friendsList.innerHTML = ''; // Clear previous list

                // Populate list with friends
                if (friends.length > 0) {
                    friends.forEach(friend => {
                        const listItem = document.createElement('li');
                        listItem.textContent = friend.name;
                        listItem.className = 'text-gray-700 p-2 bg-gray-100 rounded';
                        friendsList.appendChild(listItem);
                    });
                } else {
                    const emptyMessage = document.createElement('li');
                    emptyMessage.textContent = 'No friends found.';
                    emptyMessage.className = 'text-gray-700 p-2 bg-gray-100 rounded';
                    friendsList.appendChild(emptyMessage);
                }

                // Show the modal
                document.getElementById('friends-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error(error);
                alert('Could not load friends list. Please try again later.');
            });
    }

    // Hide the modal
    function hideFriendsList() {
        document.getElementById('friends-modal').classList.add('hidden');
    }
</script>
@endsection

