<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);
        $users = User::with('roles')->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->success('Users retrieved', [
            'items' => UserResource::collection($users->items()),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->register($request->validated(), $request->user()->id);

        return $this->success('User created successfully', new UserResource($user->load('roles')), 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if (!empty($data['role_id'])) {
            $user->roles()->sync([$data['role_id']]);
        }

        return $this->success('User updated successfully', new UserResource($user->load('roles')));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->success('User deleted successfully');
    }
}
