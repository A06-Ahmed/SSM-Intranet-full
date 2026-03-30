<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        private readonly RefreshTokenService $refreshTokenService,
        private readonly ActivityLogService $activityLogService
    ) {}

    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->with('roles')->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \RuntimeException('Invalid credentials.');
        }

        if (!$user->is_active) {
            throw new \RuntimeException('User account is inactive.');
        }

        $token = JWTAuth::fromUser($user);
        $refreshToken = $this->refreshTokenService->createForUser($user);

        $this->activityLogService->log($user->id, 'login', 'auth', 'User login');

        return $this->buildTokenResponse($token, $refreshToken, $user);
    }

    public function refresh(string $refreshToken): array
    {
        $tokenRecord = $this->refreshTokenService->validate($refreshToken);

        if (!$tokenRecord) {
            throw new \RuntimeException('Invalid refresh token.');
        }

        $user = $tokenRecord->user()->with('roles')->firstOrFail();

        if (!$user->is_active) {
            throw new \RuntimeException('User account is inactive.');
        }

        $tokenRecord->update(['revoked_at' => now()]);
        $newRefreshToken = $this->refreshTokenService->createForUser($user);

        $jwt = JWTAuth::fromUser($user);

        return $this->buildTokenResponse($jwt, $newRefreshToken, $user);
    }

    public function logout(?string $refreshToken, User $user): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        if ($refreshToken) {
            $this->refreshTokenService->revokeToken($refreshToken);
        } else {
            $this->refreshTokenService->revokeAllForUser($user);
        }
    }

    private function buildTokenResponse(string $token, string $refreshToken, User $user): array
    {
        $user->load('roles');

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}
