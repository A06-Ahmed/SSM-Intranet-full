<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_create_project(): void
    {
        $this->seed();

        $employeeRole = Role::where('name', 'Employee')->first();
        $employee = User::create([
            'first_name' => 'Emp',
            'last_name' => 'User',
            'email' => 'employee@smm.com',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);
        $employee->roles()->sync([$employeeRole->id]);

        $response = $this->actingAs($employee, 'api')->postJson('/api/projects', [
            'name' => 'Test Project',
            'description' => 'Content',
        ]);

        $response->assertStatus(403);
    }
}
