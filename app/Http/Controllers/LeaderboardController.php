<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Scores;

class LeaderboardController extends Controller
{
    public function index()
    {
        //Finds the lowest score that a user has entered
        $leaderboard = User::withCount('scores') // Total number of rounds
        ->with(['scores' => function ($query) {
            $query->select('user_id', \DB::raw('MIN(score) as best_score'))
                  ->groupBy('user_id');
        }])
        ->having('scores_count', '>', 0)
        ->get()
        ->sortBy(function ($user) {
            return $user->scores->first()->best_score ?? 0; // Sorts the leaderboard by the best score
        });

        return view('leaderboard.index', compact('leaderboard'));
    }
}
