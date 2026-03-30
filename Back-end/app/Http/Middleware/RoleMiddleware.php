<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error('Forbidden', null, 403);
            }

            return redirect()->route('admin.login');
        }

        $allowed = collect($roles)
            ->flatMap(fn ($role) => explode(',', (string) $role))
            ->map(fn ($role) => trim($role))
            ->filter()
            ->contains(function ($role) use ($user) {
                return $user->hasRole($role);
            });

        if (!$allowed) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error('Forbidden', null, 403);
            }

            abort(403);
        }

        return $next($request);
    }
}
