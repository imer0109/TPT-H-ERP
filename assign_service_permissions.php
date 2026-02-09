<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Attribution des permissions pour les services\n";
echo str_repeat("=", 50) . "\n";

// Vérifier que les tables nécessaires existent
$requiredTables = ['permissions', 'roles', 'user_permissions', 'role_user'];
$missingTables = [];

foreach ($requiredTables as $table) {
    if (!Schema::hasTable($table)) {
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "❌ Tables manquantes: " . implode(', ', $missingTables) . "\n";
    exit(1);
}

// Créer les permissions pour les services si elles n'existent pas
$servicePermissions = [
    ['nom' => 'Voir les services', 'slug' => 'services.view', 'module' => 'services', 'description' => 'Permission de voir les services'],
    ['nom' => 'Créer des services', 'slug' => 'services.create', 'module' => 'services', 'description' => 'Permission de créer des services'],
    ['nom' => 'Modifier les services', 'slug' => 'services.edit', 'module' => 'services', 'description' => 'Permission de modifier les services'],
    ['nom' => 'Supprimer les services', 'slug' => 'services.delete', 'module' => 'services', 'description' => 'Permission de supprimer les services'],
    ['nom' => 'Dashboard services', 'slug' => 'services.dashboard', 'module' => 'services', 'description' => 'Permission d\'accéder au dashboard des services']
];

foreach ($servicePermissions as $permData) {
    $existingPerm = DB::table('permissions')
        ->where('slug', $permData['slug'])
        ->first();
        
    if (!$existingPerm) {
        DB::table('permissions')->insert([
            'nom' => $permData['nom'],
            'slug' => $permData['slug'],
            'module' => $permData['module'],
            'description' => $permData['description'],
            'action' => explode('.', $permData['slug'])[1] ?? 'view',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Permission {$permData['slug']} créée\n";
    } else {
        echo "ℹ️  Permission {$permData['slug']} existe déjà\n";
    }
}

// Récupérer les rôles existants
$roles = [
    'administrateur' => [],
    'admin' => [],
    'manager' => [],
    'hr' => []
];

foreach ($roles as $roleSlug => $perms) {
    $role = DB::table('roles')->where('slug', $roleSlug)->first();
    if ($role) {
        echo "\n🔗 Attribution des permissions au rôle {$role->nom}:\n";
        
        // Récupérer les permissions correspondant à ce rôle selon la configuration
        $rolePermissions = config("static_permissions.services.{$roleSlug}", []);
        
        foreach ($rolePermissions as $permissionSlug) {
            $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
            if ($permission) {
                // Vérifier si l'attribution existe déjà dans role_permission
                $existingRolePerm = DB::table('permission_role')
                    ->where('role_id', $role->id)
                    ->where('permission_id', $permission->id)
                    ->first();
                    
                if (!$existingRolePerm) {
                    DB::table('permission_role')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    echo "  ✅ {$permissionSlug} attribuée\n";
                } else {
                    echo "  ℹ️  {$permissionSlug} déjà attribuée\n";
                }
            } else {
                echo "  ❌ Permission {$permissionSlug} non trouvée\n";
            }
        }
    } else {
        echo "⚠️  Rôle {$roleSlug} non trouvé\n";
    }
}

// Attribuer les permissions directement aux utilisateurs basés sur leurs rôles
echo "\n👥 Attribution des permissions aux utilisateurs...\n";

$users = DB::table('users')->get();
foreach ($users as $user) {
    // Récupérer les rôles de l'utilisateur
    $userRoles = DB::table('role_user')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('role_user.user_id', $user->id)
        ->pluck('roles.slug')
        ->toArray();
    
    if (!empty($userRoles)) {
        echo "  📄 {$user->prenom} {$user->nom} (rôles: " . implode(', ', $userRoles) . "):\n";
        
        foreach ($userRoles as $userRole) {
            $rolePermissions = config("static_permissions.services.{$userRole}", []);
            
            foreach ($rolePermissions as $permissionSlug) {
                $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
                if ($permission) {
                    // Vérifier si l'utilisateur a déjà cette permission
                    $existingUserPerm = DB::table('user_permissions')
                        ->where('user_id', $user->id)
                        ->where('permission_id', $permission->id)
                        ->first();
                        
                    if (!$existingUserPerm) {
                        DB::table('user_permissions')->insert([
                            'user_id' => $user->id,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        echo "    ✅ {$permissionSlug}\n";
                    }
                }
            }
        }
    }
}

echo "\n✅ Attribution terminée!\n";
echo "💡 Les utilisateurs devraient maintenant pouvoir voir le menu des services dans l'interface.\n";