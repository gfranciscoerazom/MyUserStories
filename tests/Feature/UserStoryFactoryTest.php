<?php

use App\Enums\UserStoryStatus;
use App\Models\UserStory;

it('creates a user storie with a valid story and status', function () {
    $userStory = UserStory::factory()->create();

    expect($userStory->story)->not->toBeEmpty();
    expect(in_array($userStory->status->value, UserStoryStatus::values(), true))->toBeTrue();
    expect($userStory->project_id)->toBeInt();
    expect($userStory->user_id)->toBeInt();
});
