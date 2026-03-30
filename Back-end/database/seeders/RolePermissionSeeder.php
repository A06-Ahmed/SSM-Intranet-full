<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissions = Permission::all();

        $superAdmin = Role::where('name', 'SuperAdmin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $hr = Role::where('name', 'HR')->first();
        $manager = Role::where('name', 'Manager')->first();
        $employee = Role::where('name', 'Employee')->first();
        $guest = Role::where('name', 'Guest')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync($allPermissions->pluck('id'));
        }

        if ($admin) {
            $admin->permissions()->sync(Permission::whereIn('name', [
                'users.read',
                'users.create',
                'users.update',
                'users.delete',
                'projects.create',
                'projects.read',
                'projects.update',
                'projects.delete',
                'tasks.create',
                'tasks.assign',
                'tasks.update',
                'tasks.delete',
                'roles.manage',
                'permissions.manage',
                'reports.view',
                'system.settings',
            ])->pluck('id'));
        }

        if ($manager) {
            $manager->permissions()->sync(Permission::whereIn('name', [
                'projects.read',
                'tasks.create',
                'tasks.assign',
                'tasks.update',
                'reports.view',
            ])->pluck('id'));
        }

        if ($hr) {
            $hr->permissions()->sync(Permission::whereIn('name', [
                'users.read',
                'reports.view',
            ])->pluck('id'));
        }

        if ($employee) {
            $employee->permissions()->sync(Permission::whereIn('name', [
                'projects.read',
                'tasks.update',
            ])->pluck('id'));
        }

        if ($guest) {
            $guest->permissions()->sync(Permission::whereIn('name', [
                'projects.read',
            ])->pluck('id'));
        }
    }
}
