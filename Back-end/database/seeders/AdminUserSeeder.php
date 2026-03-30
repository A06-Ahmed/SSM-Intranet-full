<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'SuperAdmin')->first();

        $user = User::updateOrCreate(
            ['email' => 'admin@smm.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make('Admin123!'),
                'is_active' => true,
            ]
        );

        if ($role) {
            $user->roles()->sync([$role->id]);
        }
    }
}
