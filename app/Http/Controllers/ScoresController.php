<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Scores;

class ScoresController extends Controller
{

    public function index()
    {
        $rounds = auth()->user()->scores()->latest()->paginate(10);
        return view('scores.index', compact('scores'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0',
            'date' => 'required|date',
        ]);
    
        Scores::create([
            'user_id' => auth()->id(),
            'course_name' => $request->course_name,
            'score' => $request->score,
            'date' => $request->date,
        ]);
    
        return redirect()->route('dashboard')->with('success', 'Round added successfully!');
    }


    public function create()
    {
        return view('scores.create');
    }


    
}