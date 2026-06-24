<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Alex Morgan',
            'email' => 'alexmorgan@pritech.test',
        ]);

        $member = User::factory()->create([
            'name' => 'Jamie Lee',
            'email' => 'jamielee@pritech.test',
        ]);

        $reviewer = User::factory()->create([
            'name' => 'Taylor Kim',
            'email' => 'taylorkim@pritech.test',
        ]);
        $users = collect([$owner, $member, $reviewer]);

        $tags = Tag::factory()->createMany([
            ['name' => 'Bug', 'color' => '#dc2626'],
            ['name' => 'Feature', 'color' => '#2563eb'],
            ['name' => 'Backend', 'color' => '#7c3aed'],
            ['name' => 'Frontend', 'color' => '#059669'],
            ['name' => 'Documentation', 'color' => '#d97706'],
        ]);

        $projects = $owner->projects()->saveMany(
            Project::factory()
                ->count(5)
                ->make()
        );

        $projects->each(
            function (Project $project) use ($tags, $users): void {
                Issue::factory()
                    ->count(4)
                    ->for($project)
                    ->create()
                    ->each(
                        function (Issue $issue) use ($tags, $users): void {
                            $tagIds = $tags
                                ->random(random_int(1, 3))
                                ->pluck('id');

                            $issue->tags()->attach($tagIds);

                            $memberIds = $users
                                ->random(random_int(1, 2))
                                ->pluck('id');

                            $issue->members()->attach($memberIds);

                            Comment::factory()
                                ->count(2)
                                ->for($issue)
                                ->create();
                        }
                    );
            }
        );
    }
}
