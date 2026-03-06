<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Auto-login middleware: automatically authenticates as User 1 (Admin)
 * when no user is logged in. This removes the need for manual login
 * during development/testing. Remove this middleware when ready for production.
 */
class AutoLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::find(1);
            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}
