<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔄 Attribution des permissions aux utilisateurs via leurs rôles\n";
echo str_repeat("=", 60) . "\n";

// Récupérer tous les utilisateurs
$users = DB::table('users')->get();

foreach ($users as $user) {
    echo "👤 Traitement de {$user->prenom} {$user->nom} ({$user->email})\n";
    
    // Récupérer les rôles de l'utilisateur
    $userRoles = DB::table('role_user')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('role_user.user_id', $user->id)
        ->pluck('roles.id')
        ->toArray();
    
    if (empty($userRoles)) {
        echo "  ⚠️  Aucun rôle trouvé\n";
        continue;
    }
    
    echo "  📋 Rôles: " . implode(', ', $userRoles) . "\n";
    
    // Récupérer toutes les permissions des rôles de l'utilisateur
    $rolePermissions = DB::table('permission_role')
        ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
        ->whereIn('permission_role.role_id', $userRoles)
        ->pluck('permissions.id')
        ->unique()
        ->toArray();
    
    echo "  🔑 Permissions trouvées: " . count($rolePermissions) . "\n";
    
    // Attribuer ces permissions à l'utilisateur
    $permissionsAdded = 0;
    foreach ($rolePermissions as $permissionId) {
        // Vérifier si la permission existe déjà
        $existingPermission = DB::table('user_permissions')
            ->where('user_id', $user->id)
            ->where('permission_id', $permissionId)
            ->first();
            
        if (!$existingPermission) {
            DB::table('user_permissions')->insert([
                'user_id' => $user->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $permissionsAdded++;
        }
    }
    
    echo "  ✅ {$permissionsAdded} permissions ajoutées\n\n";
}

echo "✅ Attribution terminée!\n";
echo "\n🔍 Vérification des résultats:\n";

// Test des permissions pour les utilisateurs clés
$testUsers = [
    'admin@tpt-h.com',
    'hr@tpt-h.com', 
    'accounting@tpt-h.com',
    'purchases@tpt-h.com',
    'fournisseur@tpt-h.com'
];

foreach ($testUsers as $email) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        $permissionCount = DB::table('user_permissions')
            ->where('user_id', $user->id)
            ->count();
            
        $roleNames = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->pluck('roles.nom')
            ->toArray();
            
        echo "{$user->prenom} {$user->nom}: {$permissionCount} permissions (" . implode(', ', $roleNames) . ")\n";
    }
}