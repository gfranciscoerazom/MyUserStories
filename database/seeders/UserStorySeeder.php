<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\UserStory;
use Illuminate\Database\Seeder;

class UserStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::all()->each(function (Project $project) {
            UserStory::factory()
                ->count(3)
                ->for($project)
                ->create([
                    'user_id' => $project->user_id,
                ]);
        });
    }
}
