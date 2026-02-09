<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Initialisation des permissions avec le service...\n";

// Liste des utilisateurs
$users = [
    'admin@tpt-h.com',
    'manager@tpt-h.com', 
    'supervisor@tpt-h.com',
    'agent@tpt-h.com',
    'viewer@tpt-h.com',
    'hr@tpt-h.com',
    'accounting@tpt-h.com',
    'purchases@tpt-h.com',
    'fournisseur@tpt-h.com'
];

foreach ($users as $email) {
    echo "Traitement de: $email\n";
    
    $user = DB::table('users')->where('email', $email)->first();
    if (!$user) {
        echo "  ❌ Utilisateur non trouvé\n";
        continue;
    }
    
    echo "  ✅ Utilisateur trouvé (ID: {$user->id})\n";
    
    // Donner l'accès direct aux modules selon le type d'utilisateur
    $modulesToGrant = [];
    
    switch($email) {
        case 'admin@tpt-h.com':
            $modulesToGrant = ['hr', 'accounting', 'purchases', 'suppliers', 'clients', 'cash', 'inventory', 'users', 'companies', 'agencies'];
            break;
        case 'hr@tpt-h.com':
            $modulesToGrant = ['hr'];
            break;
        case 'accounting@tpt-h.com':
            $modulesToGrant = ['accounting'];
            break;
        case 'purchases@tpt-h.com':
            $modulesToGrant = ['purchases', 'suppliers'];
            break;
        case 'fournisseur@tpt-h.com':
            $modulesToGrant = ['suppliers'];
            break;
        case 'manager@tpt-h.com':
            $modulesToGrant = ['hr', 'accounting', 'purchases', 'clients'];
            break;
        case 'supervisor@tpt-h.com':
            $modulesToGrant = ['hr', 'accounting'];
            break;
        case 'agent@tpt-h.com':
            $modulesToGrant = ['clients', 'cash'];
            break;
        case 'viewer@tpt-h.com':
            $modulesToGrant = ['clients'];
            break;
    }
    
    echo "  Modules à attribuer: " . implode(', ', $modulesToGrant) . "\n";
    
    // Attribuer les permissions directement via le système de permissions
    foreach ($modulesToGrant as $module) {
        // Créer une permission simple pour le module
        $permissionSlug = $module . '.access';
        $permissionName = 'Accès ' . ucfirst($module);
        
        // Vérifier si la permission existe
        $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
        if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'nom' => $permissionName,
                'slug' => $permissionSlug,
                'module' => $module,
                'description' => "Permission d'accès au module {$module}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "    ✅ Permission {$permissionSlug} créée\n";
        } else {
            $permissionId = $permission->id;
            echo "    ℹ️  Permission {$permissionSlug} existe déjà\n";
        }
        
        // Attribuer la permission à l'utilisateur
        DB::table('user_permissions')->updateOrInsert(
            ['user_id' => $user->id, 'permission_id' => $permissionId],
            ['user_id' => $user->id, 'permission_id' => $permissionId]
        );
        echo "    ✅ Permission {$permissionSlug} attribuée\n";
    }
}

echo "\n✅ Initialisation terminée!\n";

// Vérification finale
echo "\nVérification des accès:\n";
foreach ($users as $email) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        $accessibleModules = DB::table('user_permissions')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->where('user_permissions.user_id', $user->id)
            ->pluck('permissions.module')
            ->unique()
            ->toArray();
        
        echo "{$email}: " . implode(', ', $accessibleModules) . "\n";
    }
}

echo "\nTest des accès via canAccessModule:\n";
// Tester avec le modèle User
$userModel = app('App\Models\User');
$testUser = $userModel->where('email', 'hr@tpt-h.com')->first();
if ($testUser) {
    echo "Test HR user access:\n";
    $modules = ['hr', 'accounting', 'purchases'];
    foreach ($modules as $module) {
        $canAccess = $testUser->canAccessModule($module);
        echo "  {$module}: " . ($canAccess ? '✅' : '❌') . "\n";
    }
}