<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
 public function run(): void
    {
        $tags = Tag::factory()->createMany([
            ['name' => 'Bug', 'color' => '#dc2626'],
            ['name' => 'Feature', 'color' => '#2563eb'],
            ['name' => 'Backend', 'color' => '#7c3aed'],
            ['name' => 'Frontend', 'color' => '#059669'],
            ['name' => 'Documentation', 'color' => '#d97706'],
        ]);

        Project::factory()
            ->count(5)
            ->create()
            ->each(function (Project $project) use ($tags): void {
                Issue::factory()
                    ->count(4)
                    ->for($project)
                    ->create()
                    ->each(function (Issue $issue) use ($tags): void {
                        $tagIds = $tags
                            ->random(random_int(1, 3))
                            ->pluck('id');

                        $issue->tags()->attach($tagIds);

                        Comment::factory()
                            ->count(2)
                            ->for($issue)
                            ->create();
                    });
            });
    }
}
