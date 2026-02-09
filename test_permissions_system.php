<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test du système de permissions\n";
echo str_repeat("=", 50) . "\n";

// Liste des utilisateurs de test
$testUsers = [
    'admin@tpt-h.com' => 'password',
    'hr@tpt-h.com' => 'password',
    'accounting@tpt-h.com' => 'password',
    'purchases@tpt-h.com' => 'password',
    'fournisseur@tpt-h.com' => 'password'
];

$modulesToTest = ['hr', 'accounting', 'purchases', 'suppliers', 'clients', 'cash', 'inventory'];

echo "\n📋 Test des permissions pour chaque utilisateur:\n";
echo str_repeat("-", 80) . "\n";

foreach ($testUsers as $email => $password) {
    $user = DB::table('users')->where('email', $email)->first();
    
    if (!$user) {
        echo "❌ Utilisateur {$email} non trouvé\n";
        continue;
    }
    
    echo "👤 {$user->prenom} {$user->nom} ({$email}):\n";
    
    // Test de connexion
    if (Hash::check($password, $user->password)) {
        echo "  ✅ Connexion OK\n";
    } else {
        echo "  ❌ Mot de passe incorrect\n";
        continue;
    }
    
    // Test des permissions par module
    foreach ($modulesToTest as $module) {
        $canAccess = DB::table('user_permissions')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->where('user_permissions.user_id', $user->id)
            ->where('permissions.module', $module)
            ->exists();
            
        $status = $canAccess ? '✅' : '❌';
        echo "    {$status} Module {$module}\n";
    }
    
    echo "\n";
}

// Test des rôles attribués
echo "\n👥 Rôles attribués aux utilisateurs:\n";
echo str_repeat("-", 50) . "\n";

foreach ($testUsers as $email => $password) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        $roles = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->pluck('roles.nom')
            ->toArray();
            
        echo "{$user->prenom} {$user->nom}: " . implode(', ', $roles) . "\n";
    }
}

// Test des permissions par rôle
echo "\n🔑 Permissions par rôle:\n";
echo str_repeat("-", 50) . "\n";

$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    $permissions = DB::table('permission_role')
        ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
        ->where('permission_role.role_id', $role->id)
        ->pluck('permissions.nom')
        ->toArray();
        
    echo "{$role->nom} ({$role->slug}):\n";
    foreach (array_chunk($permissions, 3) as $permissionChunk) {
        echo "  " . implode(' | ', $permissionChunk) . "\n";
    }
    echo "\n";
}

echo "\n✅ Tests terminés!\n";
echo "\n🔧 Identifiants de test:\n";
foreach ($testUsers as $email => $password) {
    echo "- {$email} / {$password}\n";
}