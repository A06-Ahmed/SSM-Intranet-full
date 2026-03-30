<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'admin_or_rh' => \App\Http\Middleware\CheckAdminOrRH::class,
        ]);

        $middleware->group('api', [
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\CompressResponse::class,
            \App\Http\Middleware\ForceJsonResponse::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $expectsJson = function ($request): bool {
            return $request->expectsJson() || $request->is('api/*');
        };

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) use ($expectsJson) {
            if (!$expectsJson($request)) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Validation failed',
                $e->errors(),
                422
            );
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) use ($expectsJson) {
            if (!$expectsJson($request)) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Resource not found',
                null,
                404
            );
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) use ($expectsJson) {
            if (!$expectsJson($request)) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Forbidden',
                null,
                403
            );
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) use ($expectsJson) {
            if (!$expectsJson($request)) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Unauthenticated',
                null,
                401
            );
        });

        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\JWTException $e, $request) use ($expectsJson) {
            if (!$expectsJson($request)) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Unauthenticated',
                null,
                401
            );
        });

        $exceptions->render(function (\Throwable $e, $request) use ($expectsJson) {
            if (!config('app.debug') && $expectsJson($request)) {
                return \App\Support\ApiResponse::error(
                    'Server error',
                    null,
                    500
                );
            }
        });
    })->create();
