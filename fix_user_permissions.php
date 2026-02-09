<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Correction des permissions des utilisateurs...\n";

// Liste des utilisateurs et leurs rôles attendus
$users = [
    'admin@tpt-h.com' => 'administrateur',
    'manager@tpt-h.com' => 'manager',
    'supervisor@tpt-h.com' => 'supervisor',
    'agent@tpt-h.com' => 'operational',
    'viewer@tpt-h.com' => 'viewer',
    'hr@tpt-h.com' => 'hr',
    'accounting@tpt-h.com' => 'accounting',
    'purchases@tpt-h.com' => 'purchases',
    'fournisseur@tpt-h.com' => 'suppliers'
];

// Configuration des permissions par module
$config = config('static_permissions', []);

foreach ($users as $email => $expectedRole) {
    echo "\nTraitement de l'utilisateur: $email\n";
    
    $user = DB::table('users')->where('email', $email)->first();
    if (!$user) {
        echo "  ❌ Utilisateur non trouvé\n";
        continue;
    }
    
    echo "  ✅ Utilisateur trouvé (ID: {$user->id})\n";
    
    // Vérifier si l'utilisateur a déjà un rôle
    $existingRole = DB::table('role_user')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('role_user.user_id', $user->id)
        ->select('roles.slug', 'roles.nom')
        ->first();
    
    if ($existingRole) {
        echo "  ℹ️  Rôle actuel: {$existingRole->nom} ({$existingRole->slug})\n";
        
        // Si le rôle n'est pas le bon, le mettre à jour
        if ($existingRole->slug !== $expectedRole) {
            echo "  ⚠️  Mise à jour du rôle de {$existingRole->slug} vers {$expectedRole}\n";
            
            // Trouver le rôle attendu
            $newRole = DB::table('roles')->where('slug', $expectedRole)->first();
            if ($newRole) {
                DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->update([
                        'role_id' => $newRole->id,
                        'assigned_at' => now(),
                        'updated_at' => now()
                    ]);
                echo "  ✅ Rôle mis à jour\n";
            } else {
                echo "  ❌ Rôle {$expectedRole} non trouvé dans la base de données\n";
            }
        }
    } else {
        echo "  ⚠️  Aucun rôle trouvé, attribution du rôle {$expectedRole}\n";
        
        // Créer le rôle s'il n'existe pas
        $role = DB::table('roles')->where('slug', $expectedRole)->first();
        if (!$role) {
            $roleName = match($expectedRole) {
                'administrateur' => 'Administrateur Système',
                'manager' => 'Gestionnaire',
                'supervisor' => 'Superviseur',
                'operational' => 'Agent Opérationnel',
                'viewer' => 'Consultant',
                'hr' => 'Ressources Humaines',
                'accounting' => 'Comptabilité',
                'purchases' => 'Achats',
                'suppliers' => 'Fournisseurs',
                default => ucfirst($expectedRole)
            };
            
            $roleId = DB::table('roles')->insertGetId([
                'nom' => $roleName,
                'slug' => $expectedRole,
                'description' => "Rôle {$roleName}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "  ✅ Rôle {$expectedRole} créé\n";
        } else {
            $roleId = $role->id;
        }
        
        // Attribuer le rôle à l'utilisateur
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roleId,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "  ✅ Rôle attribué à l'utilisateur\n";
    }
    
    // Vérifier et attribuer les permissions
    $modulesToCheck = ['hr', 'accounting', 'purchases', 'suppliers', 'clients', 'cash', 'inventory', 'users'];
    
    foreach ($modulesToCheck as $module) {
        echo "    Module {$module}: ";
        
        // Vérifier si l'utilisateur a déjà accès à ce module
        $hasAccess = DB::table('user_permissions')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->where('user_permissions.user_id', $user->id)
            ->where('permissions.module', $module)
            ->exists();
        
        if (!$hasAccess) {
            // Créer et attribuer les permissions de base pour ce module
            $basePermissions = [
                "{$module}.view",
                "{$module}.dashboard"
            ];
            
            if ($expectedRole === 'administrateur' || $expectedRole === 'admin') {
                $basePermissions = array_merge($basePermissions, [
                    "{$module}.create",
                    "{$module}.edit",
                    "{$module}.delete"
                ]);
            } elseif (in_array($expectedRole, ['manager', $module])) {
                $basePermissions = array_merge($basePermissions, [
                    "{$module}.create",
                    "{$module}.edit"
                ]);
            }
            
            foreach ($basePermissions as $permissionSlug) {
                // Créer la permission si elle n'existe pas
                $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
                if (!$permission) {
                    $permissionId = DB::table('permissions')->insertGetId([
                        'nom' => ucfirst(str_replace(['.', '_'], ' ', $permissionSlug)),
                        'slug' => $permissionSlug,
                        'module' => $module,
                        'description' => "Permission {$permissionSlug}",
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $permissionId = $permission->id;
                }
                
                // Attribuer la permission à l'utilisateur
                DB::table('user_permissions')->updateOrInsert(
                    ['user_id' => $user->id, 'permission_id' => $permissionId],
                    ['user_id' => $user->id, 'permission_id' => $permissionId]
                );
            }
            
            echo "permissions attribuées\n";
        } else {
            echo "déjà accessible\n";
        }
    }
}

echo "\n✅ Correction des permissions terminée!\n";

// Vérification finale
echo "\nVérification des accès:\n";
foreach ($users as $email => $expectedRole) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        $roles = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->pluck('roles.nom')
            ->toArray();
        
        echo "{$email} (" . implode(', ', $roles) . "): ";
        
        // Test d'accès aux modules principaux
        $modules = ['hr', 'accounting', 'purchases', 'suppliers', 'clients', 'cash', 'inventory'];
        $accessibleModules = [];
        
        foreach ($modules as $module) {
            // Vérifier si l'utilisateur a des permissions pour ce module
            $hasPermission = DB::table('user_permissions')
                ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                ->where('user_permissions.user_id', $user->id)
                ->where('permissions.module', $module)
                ->exists();
            
            if ($hasPermission) {
                $accessibleModules[] = $module;
            }
        }
        
        echo implode(', ', $accessibleModules) ?: 'aucun module';
        echo "\n";
    }
}