<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FriendUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()->count(10)->create();

        // Randomly creates friendships between users
        foreach ($users as $user) {
            $friends = $users->random(rand(1, 3));

            foreach ($friends as $friend) {
                // Avoids self-friending and duplicate friendships
                if ($user->id !== $friend->id && !$user->isFriendsWith($friend)) {
                    $user->addFriend($friend);
                }
            }
        }
    }
}
