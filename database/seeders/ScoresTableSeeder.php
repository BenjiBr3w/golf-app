<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Scores;
use App\Models\Player;

class ScoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $player = Player::first();
        if ($player) {
            $a = new Scores;
            $a->score = 74;
            $a->player_id = $player->id;
            $a->save();
        }

        Scores::factory()->count(30)->create();
    }
}
