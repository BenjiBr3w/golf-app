<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CourseTableSeeder::class);
        $this->call(PlayerTableSeeder::class);
        $this->call(ScoresTableSeeder::class);
        $this->call(HoleTableSeeder::class);
    }
}
