<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test complet du système de permissions\n";
echo str_repeat("=", 50) . "\n";

// Test des utilisateurs existants
echo "👥 Test des utilisateurs existants:\n";
$users = DB::table('users')->get();
foreach ($users as $user) {
    echo "  👤 {$user->prenom} {$user->nom} ({$user->email})\n";
    
    // Vérifier les rôles de l'utilisateur
    $userRoles = DB::table('role_user')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('role_user.user_id', $user->id)
        ->pluck('roles.nom')
        ->toArray();
    
    echo "     Rôles: " . implode(', ', $userRoles) . "\n";
    
    // Charger l'utilisateur via le modèle pour tester les permissions
    $userModel = \App\Models\User::find($user->id);
    if ($userModel) {
        $canAccessModules = [];
        $modules = ['hr', 'accounting', 'purchases', 'suppliers', 'clients', 'inventory', 'cash', 'companies', 'agencies'];
        
        foreach ($modules as $module) {
            if ($userModel->canAccessModule($module)) {
                $canAccessModules[] = $module;
            }
        }
        
        echo "     Modules accessibles: " . implode(', ', $canAccessModules) . "\n";
    }
    
    echo "\n";
}

// Test des permissions par rôle
echo "🎭 Test des permissions par rôle:\n";
$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    echo "  {$role->nom} ({$role->slug}):\n";
    
    $permissions = DB::table('permission_role')
        ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
        ->where('permission_role.role_id', $role->id)
        ->select('permissions.nom', 'permissions.module', 'permissions.action')
        ->get();
    
    $modules = [];
    foreach ($permissions as $perm) {
        if (!isset($modules[$perm->module])) {
            $modules[$perm->module] = [];
        }
        $modules[$perm->module][] = $perm->action;
    }
    
    foreach ($modules as $module => $actions) {
        echo "    {$module}: " . implode(', ', $actions) . "\n";
    }
    
    echo "\n";
}

// Test des middlewares
echo "🔒 Test des middlewares de permissions:\n";

// Test de permission spécifique
$testUser = DB::table('users')->where('email', 'hr@tpt-h.com')->first();
if ($testUser) {
    $userModel = \App\Models\User::find($testUser->id);
    if ($userModel) {
        echo "  Test utilisateur HR ({$testUser->email}):\n";
        echo "    hasPermission('hr.view'): " . ($userModel->hasPermission('hr.view') ? '✅' : '❌') . "\n";
        echo "    hasPermission('accounting.view'): " . ($userModel->hasPermission('accounting.view') ? '✅' : '❌') . "\n";
        echo "    canAccessModule('hr'): " . ($userModel->canAccessModule('hr') ? '✅' : '❌') . "\n";
        echo "    canAccessModule('accounting'): " . ($userModel->canAccessModule('accounting') ? '✅' : '❌') . "\n";
    }
}

// Test de rôle
$adminUser = DB::table('users')->where('email', 'admin@tpt-h.com')->first();
if ($adminUser) {
    $userModel = \App\Models\User::find($adminUser->id);
    if ($userModel) {
        echo "  Test utilisateur admin ({$adminUser->email}):\n";
        echo "    hasRole('administrateur'): " . ($userModel->hasRole('administrateur') ? '✅' : '❌') . "\n";
        echo "    canAccessModule('hr'): " . ($userModel->canAccessModule('hr') ? '✅' : '❌') . "\n";
        echo "    canAccessModule('accounting'): " . ($userModel->canAccessModule('accounting') ? '✅' : '❌') . "\n";
        echo "    getAllPermissions(): " . $userModel->getAllPermissions()->count() . " permissions\n";
    }
}

echo "\n📊 Statistiques du système:\n";
echo "  - Utilisateurs: " . DB::table('users')->count() . "\n";
echo "  - Rôles: " . DB::table('roles')->count() . "\n";
echo "  - Permissions: " . DB::table('permissions')->count() . "\n";
echo "  - Associations rôle-permission: " . DB::table('permission_role')->count() . "\n";
echo "  - Associations utilisateur-rôle: " . DB::table('role_user')->count() . "\n";
echo "  - Permissions directes utilisateur: " . DB::table('user_permissions')->count() . "\n";

echo "\n✅ Tests terminés!\n";