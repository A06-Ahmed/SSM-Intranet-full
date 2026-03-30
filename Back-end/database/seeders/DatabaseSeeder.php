<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
