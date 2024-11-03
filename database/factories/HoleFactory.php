<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hole>
 */
class HoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'length'=> fake()->randomFloat(1, 50, 700),
            'par'=> fake()->numberBetween(3,5),
            'course_id'=> fake()->numberBetween(1,4),
        ];
    }
}
