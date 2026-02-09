<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module, string $action = 'view'): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Vérifier les permissions de l'utilisateur
        if (!$user->canAccessModule($module, $action)) {
            // Log l'accès refusé
            \Log::warning('Accès refusé', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'module' => $module,
                'action' => $action,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Accès non autorisé',
                    'message' => "Vous n'avez pas la permission d'accéder à ce module ({$module}.{$action})"
                ], 403);
            }
            
            // Pour les requêtes web, rediriger avec message d'erreur
            return redirect()->back()
                ->with('error', "Accès non autorisé au module {$module}")
                ->with('module', $module)
                ->with('action', $action);
        }

        // Ajouter les informations de permission à la requête
        $request->attributes->set('user_permissions', [
            'module' => $module,
            'action' => $action,
            'user_id' => $user->id
        ]);

        return $next($request);
    }
}