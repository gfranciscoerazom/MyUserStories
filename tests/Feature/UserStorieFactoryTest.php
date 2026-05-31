<?php

use App\Enums\UserStorieStatus;
use App\Models\UserStorie;

it('creates a user storie with a valid story and status', function () {
    $userStorie = UserStorie::factory()->create();

    expect($userStorie->story)->not->toBeEmpty();
    expect(in_array($userStorie->status->value, UserStorieStatus::values(), true))->toBeTrue();
    expect($userStorie->project_id)->toBeInt();
    expect($userStorie->user_id)->toBeInt();
});
