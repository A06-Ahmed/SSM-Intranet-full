<?php

use App\Http\Controllers\Api\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Api\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Api\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\GalleryController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'me']);
        Route::post('register', [AuthController::class, 'register'])->middleware('permission:users.create');
    });
});

Route::get('health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

Route::get('news', [NewsController::class, 'index']);
Route::get('news/{news}', [NewsController::class, 'show']);
Route::get('announcements', [AnnouncementController::class, 'index']);
Route::get('announcements/{announcement}', [AnnouncementController::class, 'show']);
Route::get('gallery', [GalleryController::class, 'index']);
Route::get('notifications', [NotificationController::class, 'index']);
Route::get('public/employees', [EmployeeController::class, 'publicIndex']);

Route::middleware('auth:api')->group(function () {
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('departments/{department}', [DepartmentController::class, 'show']);
    Route::post('departments', [DepartmentController::class, 'store'])->middleware('admin_or_rh');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->middleware('admin_or_rh');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->middleware('admin_or_rh');

    Route::get('employees', [EmployeeController::class, 'index']);
    Route::get('employees/{employee}', [EmployeeController::class, 'show']);

    Route::post('announcements', [AnnouncementController::class, 'store'])->middleware('role:Admin,SuperAdmin,HR');
    Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->middleware('role:Admin,SuperAdmin,HR');
    Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('role:Admin,SuperAdmin,HR');

    Route::post('gallery', [GalleryController::class, 'store'])->middleware('role:Admin,SuperAdmin,Manager');
    Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])->middleware('role:Admin,SuperAdmin,Manager');

    Route::get('projects', [ProjectController::class, 'index'])->middleware('permission:projects.read');
    Route::post('projects', [ProjectController::class, 'store'])->middleware('permission:projects.create');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->middleware('permission:projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->middleware('permission:projects.delete');

    Route::get('tasks', [TaskController::class, 'index'])->middleware('permission:tasks.update');
    Route::post('tasks', [TaskController::class, 'store'])->middleware('permission:tasks.create');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->middleware('permission:tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->middleware('permission:tasks.delete');
});

Route::prefix('admin')->middleware(['auth:api', 'role:Admin,SuperAdmin'])->group(function () {
    Route::get('users', [AdminUserController::class, 'index'])->middleware('permission:users.read');
    Route::post('users', [AdminUserController::class, 'store'])->middleware('permission:users.create');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->middleware('permission:users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->middleware('permission:users.delete');

    Route::get('roles', [AdminRoleController::class, 'index'])->middleware('permission:roles.manage');
    Route::post('roles', [AdminRoleController::class, 'store'])->middleware('permission:roles.manage');
    Route::put('roles/{role}', [AdminRoleController::class, 'update'])->middleware('permission:roles.manage');
    Route::delete('roles/{role}', [AdminRoleController::class, 'destroy'])->middleware('permission:roles.manage');

    Route::get('permissions', [AdminPermissionController::class, 'index'])->middleware('permission:permissions.manage');
    Route::post('permissions', [AdminPermissionController::class, 'store'])->middleware('permission:permissions.manage');

    Route::get('employees', [AdminEmployeeController::class, 'index']);
    Route::post('employees', [AdminEmployeeController::class, 'store']);
    Route::put('employees/{employee}', [AdminEmployeeController::class, 'update']);
    Route::delete('employees/{employee}', [AdminEmployeeController::class, 'destroy']);
});
