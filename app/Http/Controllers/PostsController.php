<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view this page.');
        }

        // Retrieve friend IDs explicitly to avoid ambiguity
        $friendIds = $user->friends()->pluck('users.id');

        $friendIds->push($user->id);

        // Fetch posts from friends
        $posts = Post::whereIn('user_id', $friendIds)
            ->with(['user', 'comments.user']) // Load relationships
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts.index', compact('posts'));
    }



    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageLink = null;
        if ($request->hasFile('image')) {
            $imageLink = $request->file('image')->store('images', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_link' => $imageLink,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }



    public function storeComment(Request $request, $postId)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageLink = null;
        if ($request->hasFile('image')) {
            $imageLink = $request->file('image')->store('images', 'public');
        }


        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_link' => $imageLink,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successful!',
            'comment' => [
                'user' => ['name' => auth()->user()->name],
                'content' => $comment->content,
                'created_at' => $comment->created_at->format('d M Y, H:i'),
                'image_link' => $comment->image_link,
            ],
        ]);
    }

}
