<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserStory;

class UserStoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserStory $userStory): bool
    {
        return $user->belongsToTeam($userStory->project->team);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserStory $userStory): bool
    {
        return $user->is($userStory->user)
            || $user->is($userStory->project->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserStory $userStory): bool
    {
        return $this->update($user, $userStory);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserStory $userStory): bool
    {
        return $this->delete($user, $userStory);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserStory $userStory): bool
    {
        return $this->delete($user, $userStory);
    }
}
