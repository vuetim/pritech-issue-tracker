<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    $startDate = fake()->dateTimeBetween('-3 months', 'now');

    return [
        'name' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'start_date' => $startDate,
        'deadline' => fake()->dateTimeBetween($startDate, '+1 year'),
    ];
}
}
