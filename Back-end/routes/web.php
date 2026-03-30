<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AnnuaireController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DepartmentAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'SMM Intranet API',
    ]);
});

Route::redirect('/login', '/admin/login')->name('login');

Route::get('/admin/login-with-token', [AuthController::class, 'loginWithToken'])
    ->name('admin.login.token');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth:web', 'role:SuperAdmin,Admin,HR,RH,Manager'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:SuperAdmin,Admin,HR,RH')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
            Route::get('annuaire', [AnnuaireController::class, 'index'])->name('annuaire');
            Route::get('annuaire/list', [AnnuaireController::class, 'list'])->name('annuaire.list');
            Route::post('annuaire/manual', [AnnuaireController::class, 'storeManual'])->name('annuaire.store');
            Route::post('annuaire/import', [AnnuaireController::class, 'importExcel'])->name('annuaire.import');
        });

        Route::post('departments', [DepartmentAdminController::class, 'store'])
            ->middleware('admin_or_rh')
            ->name('departments.store');

        Route::get('departments', [DepartmentAdminController::class, 'index'])
            ->middleware('admin_or_rh')
            ->name('departments.index');

        Route::middleware('role:SuperAdmin,Admin,HR')->group(function () {
            Route::resource('announcements', AnnouncementController::class)->except(['show']);
        });

        Route::middleware('role:SuperAdmin,Admin,Manager')->group(function () {
            Route::resource('news', NewsController::class)->except(['show']);
            Route::resource('gallery', GalleryController::class)->except(['show']);
        });
    });
});

Route::get('api/admin/dashboard-stats', [DashboardController::class, 'stats'])
    ->middleware(['auth:web', 'role:SuperAdmin,Admin,HR,RH,Manager'])
    ->name('admin.dashboard.stats');

Route::get('contacts/import', [ContactImportController::class, 'index'])->name('contacts.import.form');
Route::post('contacts/import', [ContactImportController::class, 'store'])->name('contacts.import');

Route::get('/gallery-image/{filename}', function (string $filename) {
    $safeName = basename($filename);
    if ($safeName !== $filename) {
        abort(404);
    }

    $path = storage_path('app/public/gallery/'.$safeName);
    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '^[A-Za-z0-9._-]+$');

Route::get('/news-image/{filename}', function (string $filename) {
    $safeName = basename($filename);
    if ($safeName !== $filename) {
        abort(404);
    }

    $path = storage_path('app/public/news/'.$safeName);
    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '^[A-Za-z0-9._-]+$');
