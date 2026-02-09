<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RolePermissionService;
use App\Models\AuditTrail;

class EntityAccessMiddleware
{
    protected $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Handle an incoming request for entity access.
     */
    public function handle(Request $request, Closure $next, $entityType)
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

        // Check if user has access to all entities (DG role)
        if ($user->hasRole('directeur_general') || $user->hasRole('super_admin')) {
            return $next($request);
        }

        // For companies, check if user has access to specific companies
        if ($entityType === 'company') {
            $companyId = $request->route('company') ?? $request->route('id');
            
            if ($companyId) {
                $company = \App\Models\Company::find($companyId);
                if ($company && !$this->userHasAccessToCompany($user, $company)) {
                    return $this->unauthorizedResponse($request);
                }
            }
        }

        // For agencies, check if user has access to specific agencies
        if ($entityType === 'agency') {
            $agencyId = $request->route('agency') ?? $request->route('id');
            
            if ($agencyId) {
                $agency = \App\Models\Agency::find($agencyId);
                if ($agency && !$this->userHasAccessToAgency($user, $agency)) {
                    return $this->unauthorizedResponse($request);
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if user has access to a specific company
     */
    protected function userHasAccessToCompany($user, $company)
    {
        // Check if user is assigned to this company
        return $user->societes()->where('entity_id', $company->id)->exists();
    }

    /**
     * Check if user has access to a specific agency
     */
    protected function userHasAccessToAgency($user, $agency)
    {
        // Check if user is assigned to this agency
        return $user->agences()->where('entity_id', $agency->id)->exists();
    }

    /**
     * Return unauthorized response
     */
    protected function unauthorizedResponse($request)
    {
        // Log unauthorized access attempt
        AuditTrail::logEvent('unauthorized_entity_access', auth()->user(), null, null, [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Accès non autorisé.',
                'message' => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette entité.'
            ], 403);
        }

        return redirect()->back()->with('error', 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour accéder à cette entité.');
    }
}