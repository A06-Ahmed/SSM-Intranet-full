<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error('Forbidden', null, 403);
            }

            return redirect()->route('admin.login');
        }

        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error('Forbidden', null, 403);
            }

            abort(403);
        }

        return $next($request);
    }
}
