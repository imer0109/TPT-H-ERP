<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test de l'accès au module services\n";
echo str_repeat("=", 40) . "\n";

// Récupérer un utilisateur administrateur
$adminUser = DB::table('users')
    ->join('role_user', 'users.id', '=', 'role_user.user_id')
    ->join('roles', 'role_user.role_id', '=', 'roles.id')
    ->where('roles.slug', 'administrateur')
    ->select('users.*')
    ->first();

if ($adminUser) {
    // Charger l'utilisateur dans le modèle
    $userModel = new \App\Models\User();
    $userModel = $userModel->find($adminUser->id);
    
    echo "👤 Utilisateur: {$userModel->prenom} {$userModel->nom}\n";
    echo "📧 Email: {$userModel->email}\n";
    
    // Vérifier les rôles
    $roles = $userModel->roles->pluck('nom')->toArray();
    echo "🎭 Rôles: " . implode(', ', $roles) . "\n";
    
    // Tester l'accès au module services
    $canAccessServices = $userModel->canAccessModule('services');
    echo "🔐 Accès au module services: " . ($canAccessServices ? '✅ OUI' : '❌ NON') . "\n";
    
    // Vérifier les permissions spécifiques
    $permissions = $userModel->getAllPermissions();
    $servicePerms = $permissions->filter(function($perm) {
        return strpos($perm->slug, 'services.') === 0;
    });
    
    echo "📋 Permissions services: " . $servicePerms->count() . "\n";
    foreach ($servicePerms as $perm) {
        echo "  - {$perm->slug}\n";
    }
    
    // Tester l'accès à d'autres modules pour comparaison
    $canAccessCompanies = $userModel->canAccessModule('companies');
    $canAccessHR = $userModel->canAccessModule('hr');
    echo "🔐 Accès au module companies: " . ($canAccessCompanies ? '✅ OUI' : '❌ NON') . "\n";
    echo "🔐 Accès au module hr: " . ($canAccessHR ? '✅ OUI' : '❌ NON') . "\n";
} else {
    echo "❌ Aucun utilisateur administrateur trouvé\n";
}

echo "\n✅ Test terminé\n";