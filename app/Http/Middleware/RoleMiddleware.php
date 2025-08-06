<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return __401("Non authentifié");
        }

        // Vérifie si l'utilisateur est connecté et a le rôle requis
        if ($user->role !== $role) {
            return __403("Accès non autorisé");
        }

        return $next($request);
    }
}
