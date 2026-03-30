<?php

namespace App\Services;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Support\Str;

class RefreshTokenService
{
    public function createForUser(User $user): string
    {
        $plainToken = Str::random(80);

        RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => now()->addMinutes((int) config('jwt.refresh_ttl')),
        ]);

        return $plainToken;
    }

    public function revokeToken(string $plainToken): void
    {
        $tokenHash = hash('sha256', $plainToken);
        RefreshToken::where('token_hash', $tokenHash)->update(['revoked_at' => now()]);
    }

    public function revokeAllForUser(User $user): void
    {
        RefreshToken::where('user_id', $user->id)->update(['revoked_at' => now()]);
    }

    public function validate(string $plainToken): ?RefreshToken
    {
        $tokenHash = hash('sha256', $plainToken);

        return RefreshToken::where('token_hash', $tokenHash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();
    }
}
