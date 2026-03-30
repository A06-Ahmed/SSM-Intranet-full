<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function register(array $data, ?int $performedByUserId = null): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department_id' => $data['department_id'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (!empty($data['role_id'])) {
            $user->roles()->sync([$data['role_id']]);
        }

        $this->activityLogService->log($performedByUserId, 'create', 'users', 'User created');

        return $user;
    }

    public function updateRole(User $user, int $roleId, ?int $performedByUserId = null): User
    {
        $currentRoleIds = $user->roles()->pluck('id')->all();

        $user->roles()->sync([$roleId]);

        if (!in_array($roleId, $currentRoleIds, true)) {
            $this->activityLogService->log($performedByUserId, 'role_change', 'users', 'User role changed');
        }

        return $user;
    }
}
