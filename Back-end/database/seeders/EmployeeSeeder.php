<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employeeRole = Role::where('name', 'Employee')->first();
        $department = Department::first();

        if (!$employeeRole || !$department) {
            return;
        }

        $users = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@smm.com'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@smm.com'],
        ];

        foreach ($users as $index => $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make('Password123!'),
                    'is_active' => true,
                ]
            );

            if ($employeeRole) {
                $user->roles()->sync([$employeeRole->id]);
            }

            Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'matricule' => 'EMP-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'position' => 'Staff',
                    'phone' => '000-000-0000',
                    'office_location' => 'HQ',
                    'department_id' => $department->id,
                    'status' => 'active',
                    'hire_date' => now()->subDays(30 + ($index * 10)),
                ]
            );
        }
    }
}
