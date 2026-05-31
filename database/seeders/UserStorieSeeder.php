<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\UserStorie;
use Illuminate\Database\Seeder;

class UserStorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::all()->each(function (Project $project) {
            UserStorie::factory()
                ->count(3)
                ->for($project)
                ->create([
                    'user_id' => $project->user_id,
                ]);
        });
    }
}
