<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RolePermissionService
{
    /**
     * Create default system roles
     */
    public function createDefaultRoles($companyId)
    {
        $roles = [
            [
                'nom' => 'Super Administrateur',
                'slug' => 'super_admin',
                'description' => 'Accès complet à toutes les fonctionnalités du système',
                'color' => '#dc2626',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Directeur Général',
                'slug' => 'directeur_general',
                'description' => 'Directeur Général avec accès aux fonctions de direction',
                'color' => '#7c3aed',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Directeur Administratif et Financier',
                'slug' => 'dafc',
                'description' => 'Responsable des fonctions administratives et financières',
                'color' => '#059669',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Directeur des Ressources Humaines',
                'slug' => 'drh',
                'description' => 'Responsable de la gestion des ressources humaines',
                'color' => '#0891b2',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Chef de Service',
                'slug' => 'chef_service',
                'description' => 'Responsable d\'un service ou département',
                'color' => '#ea580c',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Chef de Stock',
                'slug' => 'chef_stock',
                'description' => 'Responsable de la gestion des stocks et inventaires',
                'color' => '#0d9488',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Comptable',
                'slug' => 'comptable',
                'description' => 'Responsable de la comptabilité et des écritures',
                'color' => '#7c2d12',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Agent de Terrain',
                'slug' => 'agent_terrain',
                'description' => 'Agent opérationnel avec accès limité',
                'color' => '#374151',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Caissier',
                'slug' => 'caissier',
                'description' => 'Responsable de la gestion de la caisse',
                'color' => '#92400e',
                'is_system' => true,
                'company_id' => $companyId
            ],
            [
                'nom' => 'Utilisateur',
                'slug' => 'utilisateur',
                'description' => 'Utilisateur standard avec accès de base',
                'color' => '#6b7280',
                'is_system' => true,
                'company_id' => $companyId
            ]
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug'], 'company_id' => $companyId],
                $roleData
            );
        }
    }

    /**
     * Create default system permissions
     */
    public function createDefaultPermissions()
    {
        // Check if permissions already exist
        if (Permission::count() > 0) {
            return; // Permissions already created
        }

        $modules = [
            'users' => ['users', 'roles', 'permissions'],
            'accounting' => ['chart_of_accounts', 'accounting_entries', 'accounting_journals'],
            'purchases' => ['purchase_requests', 'supplier_orders', 'suppliers'],
            'inventory' => ['products', 'stock_movements', 'inventories', 'warehouses'],
            'hr' => ['employees', 'contracts', 'payroll', 'leaves', 'attendances'],
            'cash' => ['cash_registers', 'cash_transactions', 'cash_sessions'],
            'clients' => ['clients', 'client_interactions', 'client_reclamations'],
            'security' => ['audit_trails', 'user_sessions', 'validation_workflows'],
            'api' => ['api_connectors', 'api_sync_logs', 'api_data_mappings'],
            'dashboard' => ['analytics', 'reports', 'exports']
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'export'];
        $permissions = [];

        foreach ($modules as $module => $resources) {
            foreach ($resources as $resource) {
                foreach ($actions as $action) {
                    $slug = strtolower($module . '.' . $resource . '.' . $action);
                    $name = ucfirst($action) . ' ' . ucfirst(str_replace('_', ' ', $resource));
                    $description = ucfirst($action) . ' access to ' . str_replace('_', ' ', $resource) . ' in ' . $module . ' module';

                    $permissions[] = [
                        'nom' => $name,
                        'slug' => $slug,
                        'description' => $description,
                        'module' => $module,
                        'resource' => $resource,
                        'action' => $action,
                        'is_system' => true,
                        'requires_validation' => in_array($action, ['create', 'edit', 'delete']),
                        'validation_level' => in_array($action, ['delete']) ? 2 : 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        // Insert permissions in chunks to avoid memory issues
        $chunks = array_chunk($permissions, 100);
        foreach ($chunks as $chunk) {
            Permission::insert($chunk);
        }
    }

    /**
     * Assign default permissions to roles
     */
    public function assignDefaultPermissions($companyId)
    {
        $rolePermissions = [
            'super_admin' => 'ALL', // All permissions
            'directeur_general' => [
                'ALL_EXCEPT' => ['users.users.delete', 'security.audit_trails.delete']
            ],
            'dafc' => [
                'accounting.*',
                'purchases.*',
                'cash.*',
                'dashboard.analytics.view',
                'dashboard.reports.*',
                'hr.payroll.*'
            ],
            'drh' => [
                'hr.*',
                'dashboard.analytics.view',
                'dashboard.reports.view',
                'users.users.view',
                'users.users.edit'
            ],
            'chef_service' => [
                'purchases.purchase_requests.*',
                'inventory.products.view',
                'inventory.stock_movements.view',
                'hr.employees.view',
                'dashboard.analytics.view'
            ],
            'chef_stock' => [
                'inventory.*',
                'purchases.supplier_orders.view',
                'purchases.supplier_orders.edit',
                'dashboard.analytics.view'
            ],
            'comptable' => [
                'accounting.*',
                'cash.cash_transactions.view',
                'cash.cash_sessions.view',
                'purchases.supplier_orders.view',
                'dashboard.reports.view'
            ],
            'caissier' => [
                'cash.*',
                'clients.clients.view',
                'clients.client_interactions.*'
            ],
            'agent_terrain' => [
                'inventory.products.view',
                'inventory.stock_movements.view',
                'clients.clients.view',
                'clients.client_interactions.view'
            ],
            'utilisateur' => [
                'dashboard.analytics.view'
            ]
        ];

        foreach ($rolePermissions as $roleSlug => $permissions) {
            $role = Role::where('slug', $roleSlug)
                       ->where('company_id', $companyId)
                       ->first();
            
            if (!$role) {
                continue;
            }

            if ($permissions === 'ALL') {
                // Assign all permissions
                $allPermissions = Permission::all();
                $role->permissions()->sync($allPermissions->pluck('id')->toArray());
            } elseif (isset($permissions['ALL_EXCEPT'])) {
                // Assign all permissions except specified ones
                $excludePermissions = Permission::whereIn('slug', $permissions['ALL_EXCEPT'])->pluck('id');
                $allPermissions = Permission::whereNotIn('id', $excludePermissions)->pluck('id');
                $role->permissions()->sync($allPermissions->toArray());
            } else {
                // Assign specific permissions
                $permissionIds = [];
                
                foreach ($permissions as $permission) {
                    if (str_ends_with($permission, '.*')) {
                        // Module wildcard
                        $modulePattern = str_replace('.*', '', $permission);
                        $modulePermissions = Permission::where('slug', 'like', $modulePattern . '.%')->pluck('id');
                        $permissionIds = array_merge($permissionIds, $modulePermissions->toArray());
                    } else {
                        // Specific permission
                        $perm = Permission::where('slug', $permission)->first();
                        if ($perm) {
                            $permissionIds[] = $perm->id;
                        }
                    }
                }
                
                $role->permissions()->sync(array_unique($permissionIds));
            }
        }
    }

    /**
     * Check if user can perform action on resource (enhanced with static permissions for user management)
     */
    public function canPerformAction(User $user, $module, $resource, $action, $entity = null)
    {
        // Check if user is active
        if (!$user->isActive()) {
            return false;
        }

        // Check if user has administrator role - they have access to everything
        // Handle both 'administrateur' and 'admin' variations
        if ($user->hasRole('administrateur') || $user->hasRole('admin')) {
            return $this->checkAdditionalConstraints($user, $entity, $action);
        }

        // Build permission slug
        $permissionSlug = strtolower($module . '.' . $resource . '.' . $action);
        
        // First check for static permissions for user management
        if ($this->hasStaticUserManagementPermission($user, $permissionSlug)) {
            return $this->checkAdditionalConstraints($user, $entity, $action);
        }
        
        // Check permission
        $hasPermission = $user->hasPermission($permissionSlug);
        
        if (!$hasPermission) {
            return false;
        }

        // Check additional constraints for specific entities
        return $this->checkAdditionalConstraints($user, $entity, $action);
    }

    /**
     * Check if user has a static user management permission based on their role
     */
    public function hasStaticUserManagementPermission(User $user, $permission)
    {
        // Only apply static permissions to user management
        if (!str_starts_with($permission, 'users.')) {
            return false;
        }
        
        // Get user roles
        $userRoles = $user->roles()->pluck('slug')->toArray();
        
        // Define static permissions for user management by role
        $staticPermissions = config('static_permissions.users', []);
        
        // Check each role against static permissions
        foreach ($userRoles as $role) {
            if (isset($staticPermissions[$role])) {
                if (in_array($permission, $staticPermissions[$role])) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check additional constraints for entities
     */
    protected function checkAdditionalConstraints(User $user, $entity, $action)
    {
        if ($entity) {
            return $this->checkEntityConstraints($user, $entity, $action);
        }

        return true;
    }

    /**
     * Check entity-specific constraints
     */
    protected function checkEntityConstraints(User $user, $entity, $action)
    {
        // Company constraint - user can only access data from their company
        if (method_exists($entity, 'company') && $entity->company_id !== $user->company_id) {
            return false;
        }

        // Time-based constraints for sensitive actions
        if (in_array($action, ['delete', 'edit']) && $this->isOutsideWorkingHours()) {
            // Check if user has special permissions for after-hours access
            return $user->hasPermission('security.after_hours_access');
        }

        // Amount-based constraints for financial operations
        if (method_exists($entity, 'amount') && $entity->amount > 100000) {
            return $user->hasPermission('accounting.high_amount_transactions');
        }

        return true;
    }

    /**
     * Check if current time is outside working hours
     */
    protected function isOutsideWorkingHours()
    {
        $currentHour = now()->hour;
        $workingHoursStart = config('security.working_hours.start', 7);
        $workingHoursEnd = config('security.working_hours.end', 19);
        
        return $currentHour < $workingHoursStart || $currentHour > $workingHoursEnd;
    }

    /**
     * Get user permissions with caching
     */
    public function getUserPermissions(User $user)
    {
        return Cache::remember(
            'user_permissions_' . $user->id,
            config('security.cache_duration', 3600),
            function () use ($user) {
                return $user->getAllPermissions();
            }
        );
    }

    /**
     * Clear user permissions cache
     */
    public function clearUserPermissionsCache(User $user)
    {
        Cache::forget('user_permissions_' . $user->id);
    }

    /**
     * Audit permission changes
     */
    public function auditPermissionChange($user, $action, $target, $details = [])
    {
        AuditTrail::logEvent($action, $target, null, null, array_merge($details, [
            'performed_by' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]));
    }

    /**
     * Get role hierarchy for user
     */
    public function getUserRoleHierarchy(User $user)
    {
        $roles = $user->roles()->with('permissions')->get();
        
        return $roles->map(function ($role) {
            return [
                'role' => $role,
                'permissions' => $role->permissions,
                'level' => $this->getRoleLevel($role->slug)
            ];
        })->sortByDesc('level');
    }

    /**
     * Get role level for hierarchy
     */
    protected function getRoleLevel($roleSlug)
    {
        $levels = [
            'super_admin' => 100,
            'directeur_general' => 90,
            'dafc' => 80,
            'drh' => 75,
            'chef_service' => 60,
            'chef_stock' => 50,
            'comptable' => 40,
            'caissier' => 30,
            'agent_terrain' => 20,
            'utilisateur' => 10
        ];

        return $levels[$roleSlug] ?? 0;
    }
}