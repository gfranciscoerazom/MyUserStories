<?php

use App\Enums\TeamRole;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new ProjectPolicy();
});

it('allows any authenticated user to view the project index', function () {
    $user = User::factory()->create();

    expect($this->policy->viewAny($user))->toBeTrue();
});

it('allows a team member to view a project', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create();

    $team->members()->attach($user, ['role' => TeamRole::Member]);
    $project = Project::factory()->create(['team_id' => $team->id]);

    expect($this->policy->view($user, $project))->toBeTrue();
});

it('denies a non-member from viewing a project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    expect($this->policy->view($user, $project))->toBeFalse();
});

it('allows a user to create a project', function () {
    $user = User::factory()->create();

    expect($this->policy->create($user))->toBeTrue();
});

it('allows an owner or admin to update a project', function () {
    $team = Team::factory()->create();
    $project = Project::factory()->create(['team_id' => $team->id]);
    $admin = User::factory()->create();
    $owner = User::factory()->create();

    $team->members()->attach($admin, ['role' => TeamRole::Admin]);
    $team->members()->attach($owner, ['role' => TeamRole::Owner]);

    expect($this->policy->update($admin, $project))->toBeTrue()
        ->and($this->policy->update($owner, $project))->toBeTrue();
});

it('denies a regular member from updating a project', function () {
    $team = Team::factory()->create();
    $project = Project::factory()->create(['team_id' => $team->id]);
    $member = User::factory()->create();

    $team->members()->attach($member, ['role' => TeamRole::Member]);

    expect($this->policy->update($member, $project))->toBeFalse();
});

it('allows only an owner to delete a project', function () {
    $team = Team::factory()->create();
    $project = Project::factory()->create(['team_id' => $team->id]);
    $owner = User::factory()->create();
    $admin = User::factory()->create();

    $team->members()->attach($owner, ['role' => TeamRole::Owner]);
    $team->members()->attach($admin, ['role' => TeamRole::Admin]);

    expect($this->policy->delete($owner, $project))->toBeTrue()
        ->and($this->policy->delete($admin, $project))->toBeFalse();
});
