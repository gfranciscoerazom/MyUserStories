<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserStorie;

class UserStoriePolicy
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
    public function view(User $user, UserStorie $userStorie): bool
    {
        return $user->belongsToTeam($userStorie->project->team);
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
    public function update(User $user, UserStorie $userStorie): bool
    {
        return $user->is($userStorie->user)
            || $user->is($userStorie->project->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserStorie $userStorie): bool
    {
        return $this->update($user, $userStorie);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserStorie $userStorie): bool
    {
        return $this->delete($user, $userStorie);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserStorie $userStorie): bool
    {
        return $this->delete($user, $userStorie);
    }
}
