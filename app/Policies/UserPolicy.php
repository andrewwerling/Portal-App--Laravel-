<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Super admins can update anyone
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Regular admins can't update super admins
        if ($model->isSuperAdmin() && !$user->isSuperAdmin()) {
            return false;
        }

        // Users can't update themselves to a different role
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Super admins can delete anyone except themselves
        if ($user->isSuperAdmin() && $user->id !== $model->id) {
            return true;
        }

        // Regular admins can't delete super admins or themselves
        if ($model->isSuperAdmin() || $user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }
}