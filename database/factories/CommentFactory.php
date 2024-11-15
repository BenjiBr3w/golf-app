<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph($nbSentences = 1, $variableNbSentences = true),
            'user_id' => fake()->numberBetween($min = 1, $max = 50),
            'post_id' => fake()->numberBetween($min = 1, $max = 100),
        ];
    }
}
