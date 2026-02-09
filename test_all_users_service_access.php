<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test de l'accès au module services pour différents rôles\n";
echo str_repeat("=", 50) . "\n";

// Récupérer des utilisateurs avec différents rôles
$users = DB::table('users')
    ->join('role_user', 'users.id', '=', 'role_user.user_id')
    ->join('roles', 'role_user.role_id', '=', 'roles.id')
    ->select('users.*', 'roles.nom as role_name', 'roles.slug as role_slug')
    ->orderBy('roles.slug')
    ->get();

foreach ($users as $userData) {
    // Charger l'utilisateur dans le modèle
    $userModel = new \App\Models\User();
    $userModel = $userModel->find($userData->id);
    
    if ($userModel) {
        echo "👤 {$userModel->prenom} {$userModel->nom} ({$userData->role_name}):\n";
        
        // Tester l'accès au module services
        $canAccessServices = $userModel->canAccessModule('services');
        echo "  🔐 Accès services: " . ($canAccessServices ? '✅ OUI' : '❌ NON') . "\n";
        
        // Vérifier les permissions spécifiques
        $permissions = $userModel->getAllPermissions();
        $servicePerms = $permissions->filter(function($perm) {
            return strpos($perm->slug, 'services.') === 0;
        });
        
        if ($servicePerms->count() > 0) {
            echo "  📋 Permissions: " . implode(', ', $servicePerms->pluck('slug')->toArray()) . "\n";
        }
        
        echo "\n";
    }
}

echo "✅ Test terminé\n";