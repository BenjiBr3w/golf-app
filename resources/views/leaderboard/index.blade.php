@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-8">Leaderboard</h1>

    <!-- Leaderboard Table -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-800 text-lg">
                    <th class="px-6 py-3 border-b border-gray-300 text-left font-semibold">Player Name</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center font-semibold">Total Rounds</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center font-semibold">Best Score</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($leaderboard as $user)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-4 border-b border-gray-300">
                            <a href="{{ route('user.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ $user->scores_count }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">
                            {{ $user->scores->first()?->best_score ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 border-b border-gray-300 text-center text-gray-500">
                            No scores available yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Notification Section for Success -->
    <div id="notification" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <p class="text-center text-lg font-semibold text-green-600" id="notification-message"></p>
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded mt-4 mx-auto block" onclick="hideNotification()">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Refresh leaderboard via AJAX
    function refreshLeaderboard() {
        fetch('/leaderboard')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch leaderboard');
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('leaderboard-container').innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching leaderboard:', error);
            });
    }

    // Display a notification
    function showNotification(message) {
        const notificationElement = document.getElementById('notification');
        const messageElement = document.getElementById('notification-message');
        messageElement.textContent = message;
        notificationElement.classList.remove('hidden');
    }

    // Hide the notification
    function hideNotification() {
        document.getElementById('notification').classList.add('hidden');
    }

    // Listen for form submission and refresh leaderboard on success
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('score-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('/scores', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to submit score');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        refreshLeaderboard();
                        showNotification('Score submitted successfully!');
                    } else {
                        alert(data.message || 'Failed to submit score.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.');
                });
            });
        }
    });
</script>
@endsection



