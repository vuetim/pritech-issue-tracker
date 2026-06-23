<?php

namespace Database\Factories;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Enums\IssueStatus;
use App\Enums\IssuePriority;



/**
 * @extends Factory<Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
{
    return [
        'project_id' => Project::factory(),
        'title' => fake()->sentence(5),
        'description' => fake()->paragraphs(2, true),
        'status' => fake()->randomElement(IssueStatus::cases()),
        'priority' => fake()->randomElement(IssuePriority::cases()),
        'due_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
    ];
}
}
