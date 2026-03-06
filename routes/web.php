<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebsiteBuilderController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/features', [HomeController::class, 'features'])->name('features');

// Auth routes (kept for compatibility but auto-login handles everything)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// All features — no login required (AutoLoginMiddleware handles user context)
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// AI Website Builder
Route::get('/builder', [WebsiteBuilderController::class, 'index'])->name('builder.index');
Route::post('/builder/generate', [WebsiteBuilderController::class, 'generate'])->name('builder.generate');
Route::post('/builder/enhance', [WebsiteBuilderController::class, 'enhance'])->name('builder.enhance');
Route::post('/builder/plan-site', [WebsiteBuilderController::class, 'planSite'])->name('builder.plan-site');
Route::post('/builder/analyze', [WebsiteBuilderController::class, 'analyze'])->name('builder.analyze');
Route::post('/builder/summarize', [WebsiteBuilderController::class, 'summarize'])->name('builder.summarize');
Route::get('/builder/{website}/status', [WebsiteBuilderController::class, 'status'])->name('builder.status');
Route::get('/builder/{website}/complete', [WebsiteBuilderController::class, 'complete'])->name('builder.complete');

// Websites
Route::get('/websites', [WebsiteController::class, 'index'])->name('websites.index');
Route::get('/websites/{website}', [WebsiteController::class, 'show'])->name('websites.show');
Route::get('/websites/{website}/wp-admin', [WebsiteController::class, 'wpAdmin'])->name('websites.wp-admin');
Route::delete('/websites/{website}', [WebsiteController::class, 'destroy'])->name('websites.destroy');
Route::post('/websites/{website}/retry', [WebsiteBuilderController::class, 'retry'])->name('websites.retry');

// Chatbot (with CORS for cross-origin calls from WordPress sites)
Route::middleware(\App\Http\Middleware\CorsMiddleware::class)->group(function () {
    Route::match(['post', 'options'], '/websites/{website}/chat', [\App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::match(['get', 'options'], '/websites/{website}/chat/history', [\App\Http\Controllers\ChatbotController::class, 'history'])->name('chatbot.history');
});

// Content Studio — AI-powered content editing
Route::prefix('websites/{website}/content-studio')->name('content-studio.')->group(function () {
    Route::post('/regenerate-section', [\App\Http\Controllers\ContentStudioController::class, 'regenerateSection'])->name('regenerate');
    Route::post('/change-tone', [\App\Http\Controllers\ContentStudioController::class, 'changeTone'])->name('tone');
    Route::post('/translate', [\App\Http\Controllers\ContentStudioController::class, 'translateContent'])->name('translate');
    Route::post('/generate-variant', [\App\Http\Controllers\ContentStudioController::class, 'generateVariant'])->name('variant');
    Route::post('/expand', [\App\Http\Controllers\ContentStudioController::class, 'expandContent'])->name('expand');
    Route::post('/generate-seo', [\App\Http\Controllers\ContentStudioController::class, 'generateSeo'])->name('seo');
    Route::get('/page-map', [\App\Http\Controllers\ContentStudioController::class, 'getPageMap'])->name('page-map');
    Route::post('/social-content', [\App\Http\Controllers\ContentStudioController::class, 'generateSocialContent'])->name('social');
});

// Website Health & AI Tools
Route::prefix('websites/{website}')->group(function () {
    Route::get('/health', [\App\Http\Controllers\WebsiteHealthController::class, 'getHealth'])->name('websites.health');
    Route::post('/health/analyze', [\App\Http\Controllers\WebsiteHealthController::class, 'analyze'])->name('websites.health.analyze');
    Route::post('/health/auto-fix', [\App\Http\Controllers\WebsiteHealthController::class, 'autoFix'])->name('websites.health.fix');
    Route::post('/redesign', [\App\Http\Controllers\WebsiteHealthController::class, 'redesign'])->name('websites.redesign');
    Route::get('/smart-suggestions', [\App\Http\Controllers\WebsiteHealthController::class, 'smartSuggestions'])->name('websites.suggestions');
});

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
