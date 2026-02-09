<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:check {user?} {--module=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les permissions d\'un utilisateur ou de tous les utilisateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $module = $this->option('module');
        $showAll = $this->option('all');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Utilisateur avec l'ID {$userId} non trouvé.");
                return 1;
            }
            
            $this->checkUserPermissions($user, $module);
        } else {
            $users = User::with('roles')->get();
            foreach ($users as $user) {
                $this->info("=== Utilisateur: {$user->prenom} {$user->nom} (ID: {$user->id}) ===");
                $this->checkUserPermissions($user, $module, $showAll);
                $this->line('');
            }
        }

        return 0;
    }

    private function checkUserPermissions($user, $module = null, $showAll = false)
    {
        $roles = $user->roles->pluck('nom')->toArray();
        $this->line("Rôles: " . implode(', ', $roles));

        // Check administrator access
        if ($user->hasRole('administrateur') || $user->hasRole('admin')) {
            $this->info("✅ Accès administrateur - accès à tous les modules");
            return;
        }

        // Get all modules from config
        $modules = array_keys(config('static_permissions', []));
        $modules = array_filter($modules, function($m) { return $m !== 'descriptions'; });

        foreach ($modules as $moduleName) {
            if ($module && $moduleName !== $module) {
                continue;
            }

            $canAccess = $user->canAccessModule($moduleName);
            $status = $canAccess ? '✅' : '❌';
            $this->line("{$status} Module {$moduleName}: " . ($canAccess ? 'Accès autorisé' : 'Accès refusé'));

            // Show detailed permissions if requested or if there's an issue
            if ($showAll || !$canAccess) {
                $this->showModulePermissions($user, $moduleName);
            }
        }
    }

    private function showModulePermissions($user, $module)
    {
        $this->line("  Permissions détaillées pour {$module}:");
        
        // Check direct permissions
        $directPermissions = $user->permissions()->where('module', $module)->get();
        if ($directPermissions->count() > 0) {
            $this->line("    Permissions directes:");
            foreach ($directPermissions as $perm) {
                $this->line("      - {$perm->nom} ({$perm->slug})");
            }
        }

        // Check role permissions
        $rolePermissions = $user->roles()->whereHas('permissions', function($query) use ($module) {
            $query->where('module', $module);
        })->with('permissions')->get();

        if ($rolePermissions->count() > 0) {
            $this->line("    Permissions par rôle:");
            foreach ($rolePermissions as $role) {
                $perms = $role->permissions->where('module', $module);
                if ($perms->count() > 0) {
                    $this->line("      Rôle {$role->nom}:");
                    foreach ($perms as $perm) {
                        $this->line("        - {$perm->nom} ({$perm->slug})");
                    }
                }
            }
        }

        // Check static permissions
        $userRoles = $user->roles()->pluck('slug')->toArray();
        $staticPermissions = config("static_permissions.{$module}", []);
        
        if (!empty($staticPermissions)) {
            $this->line("    Permissions statiques:");
            foreach ($userRoles as $role) {
                if (isset($staticPermissions[$role])) {
                    $this->line("      Rôle {$role}:");
                    foreach ($staticPermissions[$role] as $perm) {
                        $this->line("        - {$perm}");
                    }
                }
            }
        }
    }
}