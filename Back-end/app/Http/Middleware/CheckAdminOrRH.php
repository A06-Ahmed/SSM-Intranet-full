<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrRH
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401);
        }

        $roles = $user->roles()->pluck('name')->all();
        $allowed = ['SuperAdmin', 'Admin', 'HR'];

        foreach ($roles as $role) {
            if (in_array($role, $allowed, true)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
