<?php

namespace App\Policies;

use App\Models\EquipmentMaintenanceLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentMaintenanceLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user', 'maintenance_staff']);
    }

    public function view(User $user, EquipmentMaintenanceLog $log): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'it_user', 'maintenance_staff']);
    }

    public function create(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin', 'maintenance_staff']);
    }

    public function update(User $user, EquipmentMaintenanceLog $log): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']) ||
               ($user->hasRole('maintenance_staff') && $log->performed_by === $user->id);
    }

    public function delete(User $user, EquipmentMaintenanceLog $log): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'it_admin']);
    }
}
