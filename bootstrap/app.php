<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Auto-login as User 1 (no manual login needed during dev)
        $middleware->web(append: [
            \App\Http\Middleware\AutoLoginMiddleware::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'api.key' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ]);

        // Disable CSRF for chatbot API (called from WordPress sites cross-origin)
        $middleware->validateCsrfTokens(except: [
            'websites/*/chat',
            'websites/*/chat/history',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
