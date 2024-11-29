<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph($nbSentences = 2, $variableNbSentences = true),
            'image' => fake()->imageUrl($width = 640, $height = 480),
            'user_id' => fake()->numberBetween($min = 1, $max = 50),

        ];
    }
}
