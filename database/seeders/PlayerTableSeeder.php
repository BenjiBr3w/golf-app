<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\User;
use App\Models\Scores;

class PlayerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::all()->each(function ($user) {
            // Create a player for each user
            $player = Player::create([
                'name' => fake()->name,
                'handicap' => fake()->randomFloat(1, -2.0, 2.0),
                'user_id' => $user->id, // Associates each player with a unique user
            ]);

            // Create 5 scores for each player
            Scores::factory()
                ->count(5)
                ->create([
                    'user_id' => $user->id, // Associate the scores with the user
                ]);
        });

    }
}
