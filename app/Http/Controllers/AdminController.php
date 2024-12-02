<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalPosts' => Post::count(),
            'totalComments' => Comment::count(),
            'users' => User::all(),
            'posts' => Post::with('user')->get(),
        ]);
    }

    public function updateRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user = User::findOrFail($userId);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User role updated successfully!');
    }

    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Post deleted successfully!');
    }
}
