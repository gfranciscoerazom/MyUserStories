<?php

use App\Models\Project;
use App\Models\UserStorie;
use Database\Seeders\UserStorieSeeder;

it('seeds user stories for each project', function () {
    $projects = Project::factory()->count(2)->create();

    $this->app->make(UserStorieSeeder::class)->run();

    expect(UserStorie::count())->toBe(3 * $projects->count());

    $projects->each(function (Project $project) {
        $project->refresh();

        expect($project->userStories()->count())->toBe(3);

        $project->userStories->each(fn (UserStorie $userStorie) => expect($userStorie->user_id)->toBe($project->user_id)
        );
    });
});
