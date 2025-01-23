<?php

namespace App\Policies;

use App\Models\EquipmentAssignmentLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentAssignmentLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user', 'hr_admin']);
    }

    public function view(User $user, EquipmentAssignmentLog $log): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user', 'hr_admin']) ||
               $log->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }

    public function update(User $user, EquipmentAssignmentLog $log): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }

    public function delete(User $user, EquipmentAssignmentLog $log): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }
}
