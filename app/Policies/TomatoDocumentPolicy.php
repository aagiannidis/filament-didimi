<?php

namespace App\Policies;

use App\Models\RefuelingOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use TomatoPHP\FilamentDocs\Models\Document as TomatoDocument;

class TomatoDocumentPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Operator', 'Secretarial','RefuelingOrdersManager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TomatoDocument $document): bool
    {
        if ($user->hasRole(['Secretarial','RefuelingOrdersManager'])) {
            return true;
        }

        if ($user->hasRole(['Operator'])) {
            if ($document->model_type === RefuelingOrder::class) {
                $onwerEntity = RefuelingOrder::findOrFail($document->model_id);
                if ($onwerEntity->user_id === $user->id) {
                    return true;
                }
                return false;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['Operator', 'Secretarial','RefuelingOrdersManager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TomatoDocument $document): bool
    {
        return $user->hasRole(['Secretarial','RefuelingOrdersManager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TomatoDocument $document): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TomatoDocument $document): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TomatoDocument $document): bool
    {
        return false;
    }
}
