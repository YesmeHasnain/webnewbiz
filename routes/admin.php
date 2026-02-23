<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDomainController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminServerController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWebsiteController;
use Illuminate\Support\Facades\Route;

// Admin Auth (unprotected)
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/api/realtime-stats', [AdminDashboardController::class, 'realtimeStats'])->name('admin.realtime-stats');

    // Servers
    Route::prefix('servers')->name('admin.servers.')->group(function () {
        Route::get('/', [AdminServerController::class, 'index'])->name('index');
        Route::get('/create', [AdminServerController::class, 'create'])->name('create');
        Route::post('/', [AdminServerController::class, 'store'])->name('store');
        Route::get('/{server}', [AdminServerController::class, 'show'])->name('show');
        Route::post('/{server}/provision', [AdminServerController::class, 'provision'])->name('provision');
        Route::post('/{server}/health-check', [AdminServerController::class, 'healthCheck'])->name('health-check');
        Route::delete('/{server}', [AdminServerController::class, 'destroy'])->name('destroy');
    });

    // Websites
    Route::prefix('websites')->name('admin.websites.')->group(function () {
        Route::get('/', [AdminWebsiteController::class, 'index'])->name('index');
        Route::get('/{website}', [AdminWebsiteController::class, 'show'])->name('show');
        Route::post('/{website}/suspend', [AdminWebsiteController::class, 'suspend'])->name('suspend');
        Route::post('/{website}/unsuspend', [AdminWebsiteController::class, 'unsuspend'])->name('unsuspend');
        Route::delete('/{website}', [AdminWebsiteController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::patch('/{user}/status', [AdminUserController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{user}/role', [AdminUserController::class, 'updateRole'])->name('update-role');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // Plans
    Route::prefix('plans')->name('admin.plans.')->group(function () {
        Route::get('/', [AdminPlanController::class, 'index'])->name('index');
        Route::get('/create', [AdminPlanController::class, 'create'])->name('create');
        Route::post('/', [AdminPlanController::class, 'store'])->name('store');
        Route::get('/{plan}/edit', [AdminPlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [AdminPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [AdminPlanController::class, 'destroy'])->name('destroy');
    });

    // Domains
    Route::prefix('domains')->name('admin.domains.')->group(function () {
        Route::get('/', [AdminDomainController::class, 'index'])->name('index');
        Route::post('/{domain}/verify', [AdminDomainController::class, 'verify'])->name('verify');
    });

    // Activity Logs
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('admin.activity-logs.index');
});
