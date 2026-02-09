<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create all permissions
        $this->createPermissions();
        
        // Create all roles
        $this->createRoles();
        
        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Create all system permissions
     */
    private function createPermissions()
    {
        // Define all modules and their resources
        $modules = [
            'users' => [
                'resources' => [
                    'utilisateurs' => 'utilisateurs',
                    'rôles' => 'rôles',
                    'permissions' => 'permissions',
                    'équipes' => 'équipes',
                    'départements' => 'départements'
                ],
                'label' => 'Gestion des Utilisateurs'
            ],
            'companies' => [
                'resources' => [
                    'sociétés' => 'sociétés',
                    'agences' => 'agences',
                    'politiques' => 'politiques',
                    'réglementations' => 'réglementations'
                ],
                'label' => 'Gestion des Sociétés'
            ],
            'accounting' => [
                'resources' => [
                    'plans_comptable' => 'plans_comptable',
                    'journaux_comptables' => 'journaux_comptables',
                    'écritures_comptables' => 'écritures_comptables',
                    'bilans' => 'bilans'
                ],
                'label' => 'Comptabilité'
            ],
            'purchases' => [
                'resources' => [
                    'demandes_achat' => 'demandes_achat',
                    'bons_commande' => 'bons_commande',
                    'fournisseurs' => 'fournisseurs'
                ],
                'label' => 'Achats'
            ],
            'inventory' => [
                'resources' => [
                    'produits' => 'produits',
                    'mouvements_stock' => 'mouvements_stock',
                    'inventaires' => 'inventaires',
                    'dépôts' => 'dépôts'
                ],
                'label' => 'Gestion des Stocks'
            ],
            'hr' => [
                'resources' => [
                    'employés' => 'employés',
                    'contrats' => 'contrats',
                    'paie' => 'paie',
                    'congés' => 'congés',
                    'pointages' => 'pointages'
                ],
                'label' => 'Ressources Humaines'
            ],
            'cash' => [
                'resources' => [
                    'caisses' => 'caisses',
                    'transactions' => 'transactions',
                    'sessions' => 'sessions'
                ],
                'label' => 'Gestion de la Caisse'
            ],
            'clients' => [
                'resources' => [
                    'clients' => 'clients',
                    'interactions' => 'interactions',
                    'réclamations' => 'réclamations',
                    'loyalty' => 'loyalty'
                ],
                'label' => 'Gestion des Clients'
            ],
            'security' => [
                'resources' => [
                    'journaux_audit' => 'journaux_audit',
                    'sessions_utilisateurs' => 'sessions_utilisateurs',
                    'workflows_validation' => 'workflows_validation'
                ],
                'label' => 'Sécurité'
            ],
            'api' => [
                'resources' => [
                    'connecteurs' => 'connecteurs',
                    'journaux_synchronisation' => 'journaux_synchronisation',
                    'mappings_données' => 'mappings_données'
                ],
                'label' => 'Intégrations API'
            ],
            'dashboard' => [
                'resources' => [
                    'analyses' => 'analyses',
                    'rapports' => 'rapports',
                    'exports' => 'exports'
                ],
                'label' => 'Tableaux de Bord'
            ]
        ];
        
        // Define actions for each resource
        $actions = ['view', 'create', 'edit', 'delete', 'export'];
        
        // Create permissions for each module/resource/action combination
        foreach ($modules as $moduleKey => $module) {
            foreach ($module['resources'] as $resourceKey => $resource) {
                foreach ($actions as $action) {
                    Permission::firstOrCreate([
                        'slug' => strtolower($moduleKey . '.' . $resourceKey . '.' . $action)
                    ], [
                        'nom' => ucfirst($action) . ' ' . $resource,
                        'module' => $moduleKey,
                        'resource' => $resourceKey,
                        'action' => $action,
                        'description' => ucfirst($action) . ' access to ' . $resource . ' in ' . $module['label'] . ' module',
                        'is_system' => true,
                        'requires_validation' => in_array($action, ['create', 'edit', 'delete']),
                        'validation_level' => in_array($action, ['delete']) ? 2 : 1
                    ]);
                }
            }
        }
    }

    /**
     * Create all system roles
     */
    private function createRoles()
    {
        $roles = [
            [
                'nom' => 'Administrateur Système',
                'slug' => 'administrateur',
                'description' => 'Administrateur avec accès complet à toutes les fonctionnalités',
                'color' => '#ef4444'
            ],
            [
                'nom' => 'Gestionnaire',
                'slug' => 'manager',
                'description' => 'Gestionnaire avec accès aux fonctions de supervision',
                'color' => '#3b82f6'
            ],
            [
                'nom' => 'Superviseur',
                'slug' => 'supervisor',
                'description' => 'Superviseur avec accès limité aux données de son équipe',
                'color' => '#8b5cf6'
            ],
            [
                'nom' => 'Agent Opérationnel',
                'slug' => 'agent',
                'description' => 'Agent avec accès aux opérations de base',
                'color' => '#10b981'
            ],
            [
                'nom' => 'Consultant',
                'slug' => 'viewer',
                'description' => 'Consultant avec accès en lecture seule',
                'color' => '#6b7280'
            ],
            [
                'nom' => 'Ressources Humaines',
                'slug' => 'hr',
                'description' => 'Accès à la gestion des ressources humaines',
                'color' => '#f59e0b'
            ],
            [
                'nom' => 'Comptabilité',
                'slug' => 'accounting',
                'description' => 'Accès à la gestion de la comptabilité',
                'color' => '#10b981'
            ],
            [
                'nom' => 'Achats',
                'slug' => 'purchases',
                'description' => 'Accès à la gestion des achats',
                'color' => '#8b5cf6'
            ],
            [
                'nom' => 'Fournisseur',
                'slug' => 'supplier',
                'description' => 'Accès à l\'espace fournisseur',
                'color' => '#dc2626'
            ]
        ];
        
        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles()
    {
        // Get all roles
        $adminRole = Role::where('slug', 'administrateur')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $supervisorRole = Role::where('slug', 'supervisor')->first();
        $agentRole = Role::where('slug', 'agent')->first();
        $viewerRole = Role::where('slug', 'viewer')->first();
        $hrRole = Role::where('slug', 'hr')->first();
        $accountingRole = Role::where('slug', 'accounting')->first();
        $purchasesRole = Role::where('slug', 'purchases')->first();
        $supplierRole = Role::where('slug', 'supplier')->first();
        
        if (!$adminRole || !$managerRole || !$supervisorRole || !$agentRole || !$viewerRole || !$hrRole || !$accountingRole || !$purchasesRole || !$supplierRole) {
            return;
        }
        
        // Admin gets all permissions
        $allPermissions = Permission::all();
        $adminRole->permissions()->syncWithoutDetaching($allPermissions->pluck('id'));
        
        // Manager gets all permissions except user management roles and permissions
        $managerPermissions = Permission::whereNotIn('module', ['users'])
            ->orWhere(function($query) {
                $query->where('module', 'users')
                    ->whereNotIn('resource', ['rôles', 'permissions']);
            })
            ->get();
        $managerRole->permissions()->syncWithoutDetaching($managerPermissions->pluck('id'));
        
        // Supervisor gets view and export permissions for all modules
        $supervisorPermissions = Permission::whereIn('action', ['view', 'export'])->get();
        $supervisorRole->permissions()->syncWithoutDetaching($supervisorPermissions->pluck('id'));
        
        // Agent gets basic operational permissions
        $agentPermissions = Permission::whereIn('action', ['view', 'create', 'edit'])
            ->whereNotIn('module', ['users', 'accounting'])
            ->get();
        $agentRole->permissions()->syncWithoutDetaching($agentPermissions->pluck('id'));
        
        // Viewer gets only view permissions
        $viewerPermissions = Permission::where('action', 'view')->get();
        $viewerRole->permissions()->syncWithoutDetaching($viewerPermissions->pluck('id'));
        
        // HR role gets permissions related to HR module
        $hrPermissions = Permission::where('module', 'hr')
            ->orWhere('module', 'users')
            ->get();
        $hrRole->permissions()->syncWithoutDetaching($hrPermissions->pluck('id'));
        
        // Accounting role gets permissions related to accounting module
        $accountingPermissions = Permission::where('module', 'accounting')
            ->orWhere('module', 'cash')
            ->orWhere(function($query) {
                $query->where('module', 'users')
                    ->where('resource', 'utilisateurs');
            })
            ->get();
        $accountingRole->permissions()->syncWithoutDetaching($accountingPermissions->pluck('id'));
        
        // Purchases role gets permissions related to purchases module
        $purchasesPermissions = Permission::where('module', 'purchases')
            ->orWhere('module', 'inventory')
            ->orWhere('module', 'suppliers')
            ->get();
        $purchasesRole->permissions()->syncWithoutDetaching($purchasesPermissions->pluck('id'));
        
        // Supplier role gets permissions to access supplier portal
        $supplierPermissions = Permission::where('module', 'suppliers')
            ->where('resource', 'fournisseurs')
            ->whereIn('action', ['view', 'edit'])
            ->get();
        $supplierRole->permissions()->syncWithoutDetaching($supplierPermissions->pluck('id'));
    }
}