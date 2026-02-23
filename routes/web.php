<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebsiteBuilderController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/features', [HomeController::class, 'features'])->name('features');

// Guest-only auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AI Website Builder
    Route::get('/builder', [WebsiteBuilderController::class, 'index'])->name('builder.index');
    Route::post('/builder/generate', [WebsiteBuilderController::class, 'generate'])->name('builder.generate');
    Route::get('/builder/{website}/status', [WebsiteBuilderController::class, 'status'])->name('builder.status');
    Route::get('/builder/{website}/complete', [WebsiteBuilderController::class, 'complete'])->name('builder.complete');

    // Websites
    Route::get('/websites', [WebsiteController::class, 'index'])->name('websites.index');
    Route::get('/websites/{website}', [WebsiteController::class, 'show'])->name('websites.show');
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy'])->name('websites.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
});
