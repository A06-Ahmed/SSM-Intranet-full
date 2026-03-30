<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'SuperAdmin', 'description' => 'System super administrator'],
            ['name' => 'Admin', 'description' => 'System administrator'],
            ['name' => 'HR', 'description' => 'Human resources'],
            ['name' => 'Manager', 'description' => 'Department manager'],
            ['name' => 'Employee', 'description' => 'Employee'],
            ['name' => 'Guest', 'description' => 'Guest user'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
