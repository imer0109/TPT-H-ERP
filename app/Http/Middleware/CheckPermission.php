<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RolePermissionService;
use App\Models\AuditTrail;

class CheckPermission
{
    protected $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Check if user is active
        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été suspendu. Contactez l\'administrateur.');
        }

        // Parse permission (format: module.resource.action)
        $permissionParts = explode('.', $permission);
        if (count($permissionParts) !== 3) {
            abort(403, 'Permission format invalide.');
        }

        [$module, $resource, $action] = $permissionParts;

        // Check permission
        if (!$this->rolePermissionService->canPerformAction($user, $module, $resource, $action)) {
            // Log unauthorized access attempt
            AuditTrail::logEvent('unauthorized_access_attempt', $user, null, null, [
                'requested_permission' => $permission,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Accès non autorisé.',
                    'message' => 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.'
                ], 403);
            }

            abort(403, 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }

        return $next($request);
    }
}