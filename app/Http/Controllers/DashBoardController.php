<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Use Auth facade to get the currently logged-in user

        if ($user) {
            $totalPosts = auth()->user()->posts()->count(); // Count all posts
            $totalComments = Comment::count(); // Count all comments
            $friendCount = $user->friends()->count(); // Get the count of user's friends
            $notifications = auth()->user()->notifications;
            $totalRounds = auth()->user()->scores->count();
            $bestScore = auth()->user()->scores->min('score');
            $uniqueCourses = auth()->user()->scores->groupBy('course_name')->count();
            $leaderboardSummary = User::with(['scores' => function ($query) {
                $query->select('user_id', \DB::raw('MIN(score) as best_score'))
                      ->groupBy('user_id');
            }])
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->select('users.id', 'users.name', \DB::raw('MIN(scores.score) as best_score'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('best_score', 'asc')
            ->limit(5)
            ->get();

            // Pass data to the view
            return view('dashboard', compact('totalPosts', 'totalComments', 'friendCount', 'notifications',
             'totalRounds', 'bestScore', 'uniqueCourses', 'leaderboardSummary'));
        }

        // Redirect to login if no user is logged in
        return redirect()->route('login')->with('error', 'Please log in to access your account.');
    }

    public function getFriends()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $friends = $user->friends()
            ->select('users.id as user_id', 'users.name') // Resolve ambiguity
            ->get();

        return response()->json($friends);
    }
}