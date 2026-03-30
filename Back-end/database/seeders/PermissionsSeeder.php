<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'users.create', 'module' => 'users'],
            ['name' => 'users.read', 'module' => 'users'],
            ['name' => 'users.update', 'module' => 'users'],
            ['name' => 'users.delete', 'module' => 'users'],
            ['name' => 'projects.create', 'module' => 'projects'],
            ['name' => 'projects.read', 'module' => 'projects'],
            ['name' => 'projects.update', 'module' => 'projects'],
            ['name' => 'projects.delete', 'module' => 'projects'],
            ['name' => 'tasks.create', 'module' => 'tasks'],
            ['name' => 'tasks.assign', 'module' => 'tasks'],
            ['name' => 'tasks.update', 'module' => 'tasks'],
            ['name' => 'tasks.delete', 'module' => 'tasks'],
            ['name' => 'roles.manage', 'module' => 'roles'],
            ['name' => 'permissions.manage', 'module' => 'permissions'],
            ['name' => 'reports.view', 'module' => 'reports'],
            ['name' => 'system.settings', 'module' => 'system'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
