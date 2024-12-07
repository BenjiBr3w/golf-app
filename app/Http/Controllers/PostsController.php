<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\CommentNotification;

class PostsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view this page.');
        }

        $friendIds = $user->friends()->pluck('users.id');

        $friendIds->push($user->id);

        $posts = Post::whereIn('user_id', $friendIds)
            ->with(['user', 'comments.user'])
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $imagePath = null;

        // Save the uploaded image, if any
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Create a new post with the provided content and optional image
        Post::create([
            'user_id' => auth()->id(), // Assign to the authenticated user
            'content' => $request->content, // Post content
            'image' => $imagePath, // Image path saved in 'image' column
        ]);

        // Redirect back to the posts index with a success message
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

        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new CommentNotification($comment));
        }

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

    public function myPosts()
    {
        $userPosts = auth()->user()->posts()->latest()->paginate(10);
        return view('user.myPosts', compact('userPosts'));
    }

    public function edit(Post $post)
    {
        // Ensure the user is authorised to edit the post
        if (auth()->id() !== $post->user_id) {
            abort(403, 'You are not authorised to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Ensure the user is authorised to update the post
        if (auth()->id() !== $post->user_id) {
            abort(403, 'You are not authorised to update this post.');
        }

        // Validate the form data
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }

        // Update the post
        $post->content = $request->content;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {

        if (auth()->id() !== $post->user_id) {
            abort(403, 'You are not authorised to delete this post.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

}
