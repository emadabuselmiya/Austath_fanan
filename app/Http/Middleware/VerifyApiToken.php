<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Get the token from the request header
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Remove "Bearer " from the token
        $token = str_replace('Bearer ', '', $token);

        // Find the user with the given token
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Attach the user to the request for later use
        $request->user = $user;

        return $next($request);
    }
}