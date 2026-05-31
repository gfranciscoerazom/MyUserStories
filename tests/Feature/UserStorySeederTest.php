<?php

use App\Models\Project;
use App\Models\UserStory;
use Database\Seeders\UserStorySeeder;

it('seeds user stories for each project', function () {
    $projects = Project::factory()->count(2)->create();

    $this->app->make(UserStorySeeder::class)->run();

    expect(UserStory::count())->toBe(3 * $projects->count());

    $projects->each(function (Project $project) {
        $project->refresh();

        expect($project->userStories()->count())->toBe(3);

        $project->userStories->each(fn (UserStory $userStory) => expect($userStory->user_id)->toBe($project->user_id)
        );
    });
});
