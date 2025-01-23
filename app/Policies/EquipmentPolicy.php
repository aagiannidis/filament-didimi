<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user', 'department_manager']);
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user']) ||
               $equipment->assigned_to === $user->id ||
               ($user->hasRole('department_manager') && $equipment->department_id === $user->department_id);
    }

    public function create(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }

    public function update(User $user, Equipment $equipment): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        return true;
        return $user->hasRole('admin');
    }

    public function assign(User $user, Equipment $equipment): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }
}
