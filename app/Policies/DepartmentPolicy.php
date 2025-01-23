<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'hr_admin', 'department_manager']);
    }

    public function view(User $user, Department $department): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'hr_admin']) ||
               $department->id === $user->department_id ||
               $department->manager_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'hr_admin']);
    }

    public function update(User $user, Department $department): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'hr_admin']) ||
               $department->manager_id === $user->id;
    }

    public function delete(User $user, Department $department): bool
    {
        return true;
        return $user->hasRole('admin');
    }

    public function manageEmployees(User $user, Department $department): bool
    {
        return true;
        return $user->hasAnyRole(['admin', 'hr_admin']) ||
               $department->manager_id === $user->id;
    }
}
