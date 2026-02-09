<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class FixMenuPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menus:fix-permissions {--force : Force la correction sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les permissions de menu pour tous les utilisateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Cette opération va vérifier et corriger les permissions de menu pour tous les utilisateurs. Continuer ?')) {
                return 1;
            }
        }

        $this->info('Correction des permissions de menu en cours...');

        try {
            // Récupérer tous les utilisateurs
            $users = User::with('roles', 'permissions')->get();

            $totalUsers = $users->count();
            $progressBar = $this->output->createProgressBar($totalUsers);
            $progressBar->start();

            foreach ($users as $user) {
                $this->fixUserPermissions($user);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
            
            $this->info('✅ Correction des permissions de menu terminée avec succès!');

            // Afficher un résumé
            $this->showSummary();
            
        } catch (\Exception $e) {
            $this->error('Erreur lors de la correction des permissions: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function fixUserPermissions($user)
    {
        $userId = $user->id;
        $userName = $user->prenom . ' ' . $user->nom;
        
        // Vérifier si l'utilisateur est administrateur
        if ($user->hasRole('administrateur') || $user->hasRole('admin')) {
            // Les administrateurs ont accès à tous les modules
            return;
        }

        // Pour chaque module, vérifier si l'utilisateur devrait y avoir accès
        $modules = [
            'users', 'hr', 'accounting', 'purchases', 'clients', 
            'cash', 'suppliers', 'viewer', 'operational', 
            'inventory', 'security', 'api', 'reports'
        ];

        foreach ($modules as $module) {
            $hasAccess = $user->canAccessModule($module);
            
            // Si l'utilisateur devrait avoir accès mais ne l'a pas, on essaie de le lui donner
            if (!$hasAccess) {
                $this->tryGrantModuleAccess($user, $module);
            }
        }
    }

    private function tryGrantModuleAccess($user, $module)
    {
        // Vérifier les rôles de l'utilisateur
        $userRoles = $user->roles->pluck('slug')->toArray();
        
        // Charger la configuration des permissions
        $config = config('static_permissions', []);
        
        if (isset($config[$module])) {
            foreach ($userRoles as $role) {
                if (isset($config[$module][$role])) {
                    // Trouver les permissions liées à ce module pour ce rôle
                    $rolePermissions = $config[$module][$role];
                    
                    foreach ($rolePermissions as $permissionSlug) {
                        $permission = Permission::where('slug', $permissionSlug)->first();
                        
                        if ($permission && !$user->hasPermission($permissionSlug)) {
                            // Attribuer la permission directement à l'utilisateur
                            $user->grantPermission($permission, 1); // ID 1 comme utilisateur système
                        }
                    }
                }
            }
        }
    }

    private function showSummary()
    {
        $this->info("\n=== Résumé de la correction ===");
        
        $modules = [
            'users', 'hr', 'accounting', 'purchases', 'clients', 
            'cash', 'suppliers', 'viewer', 'operational', 
            'inventory', 'security', 'api', 'reports'
        ];

        foreach ($modules as $module) {
            $usersWithAccess = User::whereHas('roles.permissions', function($query) use ($module) {
                $query->where('module', $module);
            })->orWhereHas('permissions', function($query) use ($module) {
                $query->where('module', $module);
            })->count();

            $totalUsers = User::count();
            $percentage = round(($usersWithAccess / $totalUsers) * 100, 1);
            
            $this->info("  {$module}: {$usersWithAccess}/{$totalUsers} utilisateurs ({$percentage}%)");
        }
    }
}