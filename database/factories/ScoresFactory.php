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

        $golfCourses = ['Pebble Beach', 'St. Andrews', 'Augusta National', 'Pinehurst', 'Bethpage Black'];

        return [
            'score' => fake()->numberBetween(60, 125),
            'user_id' => fake()->numberBetween(1,2),
            'course_name'=> fake()->randomElement($golfCourses),
            'date'=>fake()->date('Y-m-d'),
        ];
    }
}
