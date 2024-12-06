<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{

    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->with('comments')->get();
        $scores = $user->scores()->latest()->get();

        return view('user.show', compact('user', 'posts', 'scores'));
    }
}
