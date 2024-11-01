<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scores>
 */
class ScoresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => fake()->randomFloat(60, 150),
            'score_id' => fake()->numberBetween(1,2),
            'player_id' => fake()->numberBetween(1,2),
        ];
    }
}
