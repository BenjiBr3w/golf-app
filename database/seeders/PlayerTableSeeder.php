<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $harry = new Player;
        $harry->name = "Harry";
        $harry->handicap = 0.1;
        $harry->save();

        Player::factory()->count(50)->create();

    }
}
