<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Vérification du système de permissions\n";
echo str_repeat("=", 50) . "\n";

// Vérifier la structure de la table permissions
echo "📋 Structure de la table permissions:\n";
try {
    $columns = DB::select('DESCRIBE permissions');
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la vérification de la structure: " . $e->getMessage() . "\n";
}

echo "\n";

// Vérifier les permissions existantes
echo "🔑 Permissions existantes:\n";
try {
    $permissions = DB::table('permissions')->get();
    echo "Nombre total de permissions: " . count($permissions) . "\n";
    
    foreach ($permissions as $permission) {
        echo "  - {$permission->nom}";
        if (isset($permission->slug)) {
            echo " ({$permission->slug})";
        }
        if (isset($permission->module)) {
            echo " [{$permission->module}]";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des permissions: " . $e->getMessage() . "\n";
}

echo "\n";

// Vérifier les rôles existants
echo "🎭 Rôles existants:\n";
try {
    $roles = DB::table('roles')->get();
    echo "Nombre total de rôles: " . count($roles) . "\n";
    
    foreach ($roles as $role) {
        echo "  - {$role->nom}";
        if (isset($role->slug)) {
            echo " ({$role->slug})";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des rôles: " . $e->getMessage() . "\n";
}

echo "\n";

// Vérifier les associations rôle-permission
echo "🔗 Associations rôle-permission:\n";
try {
    $rolePermissions = DB::table('permission_role')
        ->join('roles', 'permission_role.role_id', '=', 'roles.id')
        ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
        ->select('roles.nom as role_nom', 'permissions.nom as permission_nom')
        ->get();
    
    echo "Nombre total d'associations: " . count($rolePermissions) . "\n";
    
    $grouped = [];
    foreach ($rolePermissions as $rp) {
        if (!isset($grouped[$rp->role_nom])) {
            $grouped[$rp->role_nom] = [];
        }
        $grouped[$rp->role_nom][] = $rp->permission_nom;
    }
    
    foreach ($grouped as $role => $perms) {
        echo "  {$role}: " . count($perms) . " permissions\n";
        foreach (array_slice($perms, 0, 3) as $perm) {
            echo "    - {$perm}\n";
        }
        if (count($perms) > 3) {
            echo "    ... et " . (count($perms) - 3) . " autres\n";
        }
    }
} catch (Exception $e) {
    echo "Erreur lors de la vérification des associations: " . $e->getMessage() . "\n";
}

echo "\n✅ Vérification terminée!\n";