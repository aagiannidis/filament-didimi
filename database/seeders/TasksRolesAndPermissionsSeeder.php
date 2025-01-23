<?php

namespace Database\Seeders;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TasksRolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // // Create permissions for maintenance tasks
        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'edit tasks']);
        Permission::create(['name' => 'delete tasks']);

        // State transition permissions
        Permission::create(['name' => 'start task']);    // Submitted -> Todo
        Permission::create(['name' => 'process task']);    // Todo -> In Progress
        Permission::create(['name' => 'complete task']); // In Progress -> Completed

        // Create roles and assign permissions
        Role::create(['name' => 'task manager'])
            ->givePermissionTo([
                'view tasks',
                'create tasks',
                'edit tasks',
                'delete tasks',
                'start task',
                'process task',
                'complete task'
            ]);

        Role::create(['name' => 'task user'])
            ->givePermissionTo([
                'view tasks',
                'create tasks',
                'edit tasks',
                'start task'
            ]);

    }
}
