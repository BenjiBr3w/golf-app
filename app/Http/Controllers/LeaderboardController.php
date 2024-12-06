<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Scores;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = User::withCount('scores') // Total rounds
        ->with(['scores' => function ($query) {
            $query->select('user_id', \DB::raw('MIN(score) as best_score')) // Get the best score
                  ->groupBy('user_id');
        }])
        ->having('scores_count', '>', 0)
        ->get()
        ->sortBy(function ($user) {
            return $user->scores->first()->best_score ?? 0; // Sort by the best score
        });

        return view('leaderboard.index', compact('leaderboard'));
    }
}
