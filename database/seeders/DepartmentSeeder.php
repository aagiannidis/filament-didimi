<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Create core departments with specific data
        $coreDepartments = [
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Manages employee relations, recruitment, and workplace policies',
            ],
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Manages company technology infrastructure and support',
            ],
            [
                'name' => 'Finance',
                'code' => 'FI',
                'description' => 'Handles company financial operations and reporting',
            ],
            [
                'name' => 'Operations',
                'code' => 'OP',
                'description' => 'Oversees daily business operations and logistics',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MK',
                'description' => 'Manages brand promotion and marketing strategies',
            ],
        ];

        foreach ($coreDepartments as $dept) {
            Department::factory()->create([
                'name' => $dept['name'],
                'code' => $dept['code'],
                'description' => $dept['description'],
                'manager_id' => User::factory()->create([
                    'department' => $dept['name'],
                    'role' => 'ADMIN',
                ])->id,
            ]);
        }

        // Create some additional random departments
        Department::factory(3)->create();

        // Create one inactive department
        Department::factory()->inactive()->create();

        // Create one department without a manager
        Department::factory()->withoutManager()->create();
    }
}
