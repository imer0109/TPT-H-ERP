<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;
use App\Services\RolePermissionService;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync {--force : Force la synchronisation sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchroniser les permissions avec la configuration statique';

    protected $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        parent::__construct();
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Cette opération va synchroniser toutes les permissions. Continuer ?')) {
                return 1;
            }
        }

        $this->info('Synchronisation des permissions en cours...');

        try {
            // Clear existing permissions cache
            cache()->flush();
            
            // Sync permissions for all modules
            $config = config('static_permissions', []);
            
            foreach ($config as $module => $rolePermissions) {
                if ($module === 'descriptions') continue;
                
                $this->info("Synchronisation du module: {$module}");
                
                // Create or update permissions for this module
                $this->createModulePermissions($module, $rolePermissions);
                
                // Sync role permissions
                $this->syncRolePermissions($module, $rolePermissions);
            }

            $this->info('✅ Synchronisation terminée avec succès!');
            
            // Show summary
            $this->showSummary();
            
        } catch (\Exception $e) {
            $this->error('Erreur lors de la synchronisation: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function createModulePermissions($module, $rolePermissions)
    {
        $allPermissions = [];
        
        // Collect all permissions from all roles for this module
        foreach ($rolePermissions as $role => $permissions) {
            $allPermissions = array_merge($allPermissions, $permissions);
        }
        
        // Remove duplicates
        $allPermissions = array_unique($allPermissions);
        
        foreach ($allPermissions as $permissionSlug) {
            // Extract permission name from slug
            $permissionName = $this->generatePermissionName($permissionSlug);
            
            // Extract action from slug (last part after the dot)
            $parts = explode('.', $permissionSlug);
            $action = end($parts);
            
            Permission::updateOrCreate(
                ['slug' => $permissionSlug],
                [
                    'nom' => $permissionName,
                    'module' => $module,
                    'action' => $action,
                    'description' => config("static_permissions.descriptions.{$permissionSlug}", ''),
                    'resource' => $parts[1] ?? 'general'
                ]
            );
        }
    }

    private function syncRolePermissions($module, $rolePermissions)
    {
        foreach ($rolePermissions as $roleSlug => $permissions) {
            $role = Role::where('slug', $roleSlug)->first();
            
            if (!$role) {
                $this->warn("Rôle '{$roleSlug}' non trouvé, création...");
                $role = Role::create([
                    'nom' => ucfirst($roleSlug),
                    'slug' => $roleSlug,
                    'description' => "Rôle {$roleSlug} créé automatiquement"
                ]);
            }
            
            // Get permission IDs
            $permissionIds = [];
            foreach ($permissions as $permissionSlug) {
                $permission = Permission::where('slug', $permissionSlug)->first();
                if ($permission) {
                    $permissionIds[] = $permission->id;
                }
            }
            
            // Sync role permissions
            $role->permissions()->sync($permissionIds);
            
            $this->line("  ✓ {$role->nom}: " . count($permissionIds) . " permissions synchronisées");
        }
    }

    private function generatePermissionName($slug)
    {
        // Use description from config if available
        $description = config("static_permissions.descriptions.{$slug}");
        if ($description) {
            return $description;
        }
        
        // Generate name from slug
        $parts = explode('.', $slug);
        if (count($parts) >= 3) {
            [$module, $resource, $action] = $parts;
            $actionLabels = [
                'view' => 'Voir',
                'create' => 'Créer',
                'edit' => 'Modifier',
                'delete' => 'Supprimer',
                'dashboard' => 'Tableau de bord',
                'export' => 'Exporter',
                'import' => 'Importer',
                'generate' => 'Générer'
            ];
            
            $actionLabel = $actionLabels[$action] ?? ucfirst($action);
            return "{$actionLabel} {$resource}";
        }
        
        return ucfirst(str_replace(['.', '_'], ' ', $slug));
    }

    private function showSummary()
    {
        $this->info("\n=== Résumé ===");
        $this->info("Total des rôles: " . Role::count());
        $this->info("Total des permissions: " . Permission::count());
        
        $modules = array_keys(config('static_permissions', []));
        $modules = array_filter($modules, function($m) { return $m !== 'descriptions'; });
        
        foreach ($modules as $module) {
            $modulePerms = Permission::where('module', $module)->count();
            $this->info("  {$module}: {$modulePerms} permissions");
        }
    }
}