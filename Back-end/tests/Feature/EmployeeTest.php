<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_employee(): void
    {
        $this->seed();

        $adminRole = Role::where('name', 'Admin')->first();
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin2@smm.com',
            'password' => bcrypt('Admin123!'),
            'is_active' => true,
        ]);
        $admin->roles()->sync([$adminRole->id]);

        $department = Department::first();

        $response = $this->actingAs($admin, 'api')->postJson('/api/admin/employees', [
            'matricule' => 'EMP-9999',
            'position' => 'Analyst',
            'department_id' => $department->id,
            'status' => 'active',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');
    }
}
