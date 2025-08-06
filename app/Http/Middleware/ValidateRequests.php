<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateRequests
{
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'email' => ['email'],
            'password' => ['min:8'],
            'files.*' => ['file', 'max:2048'],
            'images.*' => ['image', 'max:2048'],
        ]);

        return $next($request);
    }
}