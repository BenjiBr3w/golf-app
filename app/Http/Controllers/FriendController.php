<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $friendId = $request->input('friend_id');

        if ($user->friends()->where('friend_id', $friendId)->exists()) {
            return response()->json(['success' => false, 'message' => 'You are already friends!'], 400);
        }

        $user->friends()->attach($friendId);

        return response()->json([
            'success' => true,
            'message' => 'Friend added successfully!',
            'friendCount' => $user->friends()->count(),
        ]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $users = User::where('name', 'LIKE', "%{$searchTerm}%")
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->get();

        return view('friends.search-results', compact('users'));
    }

    public function requests()
    {
        $requests = Auth::user()->friendRequests;
        return view('friends.requests', compact('requests'));
    }

    public function accept($id)
    {
        $friendRequest = Auth::user()->friendRequests()->where('id', $id)->firstOrFail();
        $friendRequest->pivot->update(['accepted' => true]);

        return redirect()->route('friends.requests')->with('success', 'Friend request accepted!');
    }
}