<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function updateRole(UpdateUserRoleRequest $request, User $user)
    {
        $user = $this->userService->updateRole($user, $request->validated()['role_id'], auth()->id());

        return $this->success('User role updated successfully', new UserResource($user->load('roles')));
    }
}
