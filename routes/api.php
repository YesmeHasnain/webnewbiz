<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\DomainController;
use App\Http\Controllers\Api\PluginController;
use App\Http\Controllers\Api\ScreenshotController;
use App\Http\Controllers\Api\ThemeController;
use App\Http\Controllers\Api\WebsiteController;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    // Websites
    Route::apiResource('websites', WebsiteController::class)->names('api.websites');
    Route::post('/websites/generate', [WebsiteController::class, 'generate']);
    Route::get('/websites/{website}/status', [WebsiteController::class, 'status']);
    Route::post('/websites/{website}/suspend', [WebsiteController::class, 'suspend']);
    Route::post('/websites/{website}/unsuspend', [WebsiteController::class, 'unsuspend']);
    Route::post('/websites/{website}/regenerate', [WebsiteController::class, 'regenerate']);

    // Plugins
    Route::get('/websites/{website}/plugins', [PluginController::class, 'index']);
    Route::post('/websites/{website}/plugins', [PluginController::class, 'install']);
    Route::post('/websites/{website}/plugins/{slug}/activate', [PluginController::class, 'activate']);
    Route::post('/websites/{website}/plugins/{slug}/deactivate', [PluginController::class, 'deactivate']);
    Route::delete('/websites/{website}/plugins/{slug}', [PluginController::class, 'destroy']);

    // Themes
    Route::get('/websites/{website}/themes', [ThemeController::class, 'index']);
    Route::post('/websites/{website}/themes', [ThemeController::class, 'install']);
    Route::post('/websites/{website}/themes/{slug}/activate', [ThemeController::class, 'activate']);

    // Backups
    Route::get('/websites/{website}/backups', [BackupController::class, 'index']);
    Route::post('/websites/{website}/backups', [BackupController::class, 'store']);
    Route::post('/websites/{website}/backups/{backup}/restore', [BackupController::class, 'restore']);
    Route::delete('/websites/{website}/backups/{backup}', [BackupController::class, 'destroy']);
    Route::get('/websites/{website}/backups/{backup}/download', [BackupController::class, 'download']);

    // Domains
    Route::get('/websites/{website}/domains', [DomainController::class, 'index']);
    Route::post('/websites/{website}/domains', [DomainController::class, 'store']);
    Route::delete('/websites/{website}/domains/{domain}', [DomainController::class, 'destroy']);
    Route::post('/websites/{website}/domains/{domain}/verify', [DomainController::class, 'verify']);
    Route::post('/websites/{website}/domains/{domain}/ssl', [DomainController::class, 'ssl']);
    Route::post('/websites/{website}/domains/{domain}/set-primary', [DomainController::class, 'setPrimary']);

    // Screenshots
    Route::post('/websites/{website}/screenshot', [ScreenshotController::class, 'capture']);
    Route::get('/websites/{website}/screenshot', [ScreenshotController::class, 'show']);
});
