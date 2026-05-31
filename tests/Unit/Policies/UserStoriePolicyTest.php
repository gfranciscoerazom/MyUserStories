<?php

use App\Enums\TeamRole;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\UserStorie;
use App\Policies\UserStoriePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows any authenticated user to view user stories', function () {
    $user = User::factory()->create();
    $policy = new UserStoriePolicy;

    expect($policy->viewAny($user))->toBeTrue();
});

it('allows a team member to view a user story', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create();

    $team->members()->attach($user, ['role' => TeamRole::Member]);
    $project = Project::factory()->create(['team_id' => $team->id]);
    $storie = UserStorie::factory()->for($project)->create();
    $policy = new UserStoriePolicy;

    expect($policy->view($user, $storie))->toBeTrue();
});

it('denies a non-team member from viewing a user story', function () {
    $user = User::factory()->create();
    $storie = UserStorie::factory()->create();
    $policy = new UserStoriePolicy;

    expect($policy->view($user, $storie))->toBeFalse();
});

it('allows any user to create a user story', function () {
    $user = User::factory()->create();
    $policy = new UserStoriePolicy;

    expect($policy->create($user))->toBeTrue();
});

it('allows the story author or project owner to update the user story', function () {
    $team = Team::factory()->create();
    $owner = User::factory()->create();
    $author = User::factory()->create();

    $team->members()->attach($owner, ['role' => TeamRole::Owner]);
    $team->members()->attach($author, ['role' => TeamRole::Member]);

    $project = Project::factory()->create(['team_id' => $team->id, 'user_id' => $owner->id]);
    $storie = UserStorie::factory()->for($project)->create(['user_id' => $author->id]);

    $policy = new UserStoriePolicy;

    expect($policy->update($author, $storie))->toBeTrue()
        ->and($policy->update($owner, $storie))->toBeTrue();
});

it('denies a team member who is not the author or owner from updating the user story', function () {
    $team = Team::factory()->create();
    $owner = User::factory()->create();
    $author = User::factory()->create();
    $member = User::factory()->create();

    $team->members()->attach($owner, ['role' => TeamRole::Owner]);
    $team->members()->attach($author, ['role' => TeamRole::Member]);
    $team->members()->attach($member, ['role' => TeamRole::Member]);

    $project = Project::factory()->create(['team_id' => $team->id, 'user_id' => $owner->id]);
    $storie = UserStorie::factory()->for($project)->create(['user_id' => $author->id]);

    $policy = new UserStoriePolicy;

    expect($policy->update($member, $storie))->toBeFalse();
});

it('allows the story author or project owner to delete the user story', function () {
    $team = Team::factory()->create();
    $owner = User::factory()->create();
    $author = User::factory()->create();

    $team->members()->attach($owner, ['role' => TeamRole::Owner]);
    $team->members()->attach($author, ['role' => TeamRole::Member]);

    $project = Project::factory()->create(['team_id' => $team->id, 'user_id' => $owner->id]);
    $storie = UserStorie::factory()->for($project)->create(['user_id' => $author->id]);

    $policy = new UserStoriePolicy;

    expect($policy->delete($author, $storie))->toBeTrue()
        ->and($policy->delete($owner, $storie))->toBeTrue();
});
