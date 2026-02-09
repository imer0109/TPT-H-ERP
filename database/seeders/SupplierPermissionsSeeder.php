<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class SupplierPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create supplier permissions
        $this->createSupplierPermissions();
        
        // Create supplier roles
        $this->createSupplierRoles();
    }

    /**
     * Create supplier permissions
     */
    private function createSupplierPermissions()
    {
        $module = 'suppliers';
        $resources = [
            'fournisseurs',
            'supplier_orders',
            'supplier_deliveries',
            'supplier_invoices',
            'supplier_payments',
            'supplier_contracts',
            'supplier_issues',
            'supplier_ratings',
            'supplier_documents'
        ];
        
        $permissions = [];
        
        foreach ($resources as $resource) {
            // View permission
            $permissions[] = [
                'nom' => 'Voir ' . $this->getResourceLabel($resource),
                'slug' => Permission::generateSlug($module, $resource, 'view'),
                'description' => 'Voir les ' . $this->getResourceLabel($resource),
                'module' => $module,
                'resource' => $resource,
                'action' => 'view',
                'is_system' => true,
                'requires_validation' => false,
                'validation_level' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Create permission
            $permissions[] = [
                'nom' => 'Créer ' . $this->getResourceLabel($resource),
                'slug' => Permission::generateSlug($module, $resource, 'create'),
                'description' => 'Créer des ' . $this->getResourceLabel($resource),
                'module' => $module,
                'resource' => $resource,
                'action' => 'create',
                'is_system' => true,
                'requires_validation' => in_array($resource, ['supplier_orders', 'supplier_contracts', 'supplier_payments']),
                'validation_level' => in_array($resource, ['supplier_payments']) ? 2 : 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Edit permission
            $permissions[] = [
                'nom' => 'Modifier ' . $this->getResourceLabel($resource),
                'slug' => Permission::generateSlug($module, $resource, 'edit'),
                'description' => 'Modifier les ' . $this->getResourceLabel($resource),
                'module' => $module,
                'resource' => $resource,
                'action' => 'edit',
                'is_system' => true,
                'requires_validation' => in_array($resource, ['supplier_orders', 'supplier_contracts', 'supplier_payments']),
                'validation_level' => in_array($resource, ['supplier_payments']) ? 2 : 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Delete permission
            $permissions[] = [
                'nom' => 'Supprimer ' . $this->getResourceLabel($resource),
                'slug' => Permission::generateSlug($module, $resource, 'delete'),
                'description' => 'Supprimer les ' . $this->getResourceLabel($resource),
                'module' => $module,
                'resource' => $resource,
                'action' => 'delete',
                'is_system' => true,
                'requires_validation' => true,
                'validation_level' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Export permission
            $permissions[] = [
                'nom' => 'Exporter ' . $this->getResourceLabel($resource),
                'slug' => Permission::generateSlug($module, $resource, 'export'),
                'description' => 'Exporter les ' . $this->getResourceLabel($resource),
                'module' => $module,
                'resource' => $resource,
                'action' => 'export',
                'is_system' => true,
                'requires_validation' => false,
                'validation_level' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Insert permissions, avoiding duplicates
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }

    /**
     * Create supplier roles
     */
    private function createSupplierRoles()
    {
        // Supplier Manager Role
        $supplierManager = Role::firstOrCreate(
            ['slug' => 'supplier_manager'],
            [
                'nom' => 'Gestionnaire Fournisseurs',
                'description' => 'Utilisateur avec accès complet à la gestion des fournisseurs',
                'color' => '#3B82F6',
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Supplier Viewer Role
        $supplierViewer = Role::firstOrCreate(
            ['slug' => 'supplier_viewer'],
            [
                'nom' => 'Observateur Fournisseurs',
                'description' => 'Utilisateur avec accès en lecture seule aux fournisseurs',
                'color' => '#6B7280',
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Financial Manager Role
        $financialManager = Role::firstOrCreate(
            ['slug' => 'financial_manager'],
            [
                'nom' => 'Responsable Financier',
                'description' => 'Utilisateur avec accès aux paiements et contrats fournisseurs',
                'color' => '#10B981',
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Assign permissions to roles
        $this->assignPermissionsToRoles($supplierManager, $supplierViewer, $financialManager);
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles($supplierManager, $supplierViewer, $financialManager)
    {
        // Supplier Manager gets all permissions
        $supplierManagerPermissions = Permission::where('module', 'suppliers')->get();
        $supplierManager->permissions()->syncWithoutDetaching($supplierManagerPermissions->pluck('id'));
        
        // Supplier Viewer gets only view permissions
        $supplierViewerPermissions = Permission::where('module', 'suppliers')
            ->where('action', 'view')
            ->get();
        $supplierViewer->permissions()->syncWithoutDetaching($supplierViewerPermissions->pluck('id'));
        
        // Financial Manager gets specific permissions
        $financialPermissions = Permission::where('module', 'suppliers')
            ->whereIn('resource', ['supplier_payments', 'supplier_invoices', 'supplier_contracts'])
            ->get();
        $financialManager->permissions()->syncWithoutDetaching($financialPermissions->pluck('id'));
    }

    /**
     * Get human-readable label for resource
     */
    private function getResourceLabel($resource)
    {
        $labels = [
            'fournisseurs' => 'fournisseurs',
            'supplier_orders' => 'commandes fournisseurs',
            'supplier_deliveries' => 'livraisons fournisseurs',
            'supplier_invoices' => 'factures fournisseurs',
            'supplier_payments' => 'paiements fournisseurs',
            'supplier_contracts' => 'contrats fournisseurs',
            'supplier_issues' => 'réclamations fournisseurs',
            'supplier_ratings' => 'évaluations fournisseurs',
            'supplier_documents' => 'documents fournisseurs'
        ];
        
        return $labels[$resource] ?? $resource;
    }
}