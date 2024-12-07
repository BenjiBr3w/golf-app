<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function edit(Comment $comment)
        {
            // Ensures the authenticated user owns the comment
            if ($comment->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            return view('comments.edit', compact('comment'));
        }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment->update(['content' => $request->content]);

        return response()->json(['success' => true]);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

}