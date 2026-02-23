<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key');

        if (!$key) {
            return response()->json(['message' => 'API key required'], 401);
        }

        $apiKey = ApiKey::where('key', $key)->first();

        if (!$apiKey || !$apiKey->isValid()) {
            return response()->json(['message' => 'Invalid or expired API key'], 401);
        }

        $apiKey->update(['last_used_at' => now()]);
        $request->merge(['api_key' => $apiKey]);
        auth()->login($apiKey->user);

        return $next($request);
    }
}
