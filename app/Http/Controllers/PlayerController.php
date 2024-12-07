<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PlayerController extends Controller
{
    /**
     * Display a listing of the players.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $players = Player::all();
        return view('players.index', ['players' => $players]);
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);
        return view ('players.show', ['player' => $player]);
    }

    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'handicap'=> 'required|numeric',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = uniqid() . '@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $player = new Player();
        $player->name = $validatedData['name'];
        $player->handicap = $validatedData['handicap'];
        $player->user_id = $user->id;


        $player->save();

        session()->flash('message', 'Player was created.');
        return redirect()->route('players.index');
    }

    public function destroy($id)
    {

        $player = Player::findOrFail($id);
        $player->delete();

        return redirect()->route('players.index')->with('message',
            'Player was deleted.');
    }
}
