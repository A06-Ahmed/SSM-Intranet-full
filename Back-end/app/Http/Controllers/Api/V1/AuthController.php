<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use RuntimeException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly UserService $userService
    ) {}

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->validated());
        } catch (RuntimeException $e) {
            $message = $e->getMessage() === 'User account is inactive.' ? $e->getMessage() : 'Invalid credentials';
            $status = $message === 'User account is inactive.' ? 403 : 401;

            return $this->error($message, null, $status);
        }

        $data['user'] = new UserResource($data['user']);

        return $this->success('Login successful', $data);
    }

    public function refresh(RefreshRequest $request)
    {
        try {
            $data = $this->authService->refresh($request->validated()['refresh_token']);
        } catch (RuntimeException $e) {
            $message = $e->getMessage() === 'User account is inactive.' ? $e->getMessage() : 'Invalid refresh token';
            $status = $message === 'User account is inactive.' ? 403 : 401;

            return $this->error($message, null, $status);
        }

        $data['user'] = new UserResource($data['user']);

        return $this->success('Token refreshed', $data);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->input('refresh_token'), $request->user());

        return $this->success('Logout successful');
    }

    public function me(Request $request)
    {
        return $this->success('User profile', new UserResource($request->user()->load('roles')));
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->register($request->validated(), $request->user()->id);

        return $this->success('User created successfully', new UserResource($user->load('roles')), 201);
    }
}
