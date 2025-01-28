<?php

namespace App\Policies;

use App\Models\SecureDocument;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SecureDocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_secure::document');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SecureDocument $secureDocument): bool
    {
        return $user->can('view_secure::document');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_secure::document');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SecureDocument $secureDocument): bool
    {
        return $user->can('update_secure::document');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SecureDocument $secureDocument): bool
    {
        return $user->can('delete_secure::document');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SecureDocument $secureDocument): bool
    {
        return $user->can('restore_secure::document');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SecureDocument $secureDocument): bool
    {
        return $user->can('force_delete_secure::document');
    }
}
