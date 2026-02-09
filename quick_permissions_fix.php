<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Attribution des permissions de base...\n";

// Donner tous les accès à l'administrateur
$admin = DB::table('users')->where('email', 'admin@tpt-h.com')->first();
if ($admin) {
    echo "Administrateur trouvé (ID: {$admin->id})\n";
    
    // Créer le rôle admin s'il n'existe pas
    $adminRole = DB::table('roles')->where('slug', 'admin')->first();
    if (!$adminRole) {
        $roleId = DB::table('roles')->insertGetId([
            'nom' => 'Administrateur',
            'slug' => 'admin',
            'description' => 'Administrateur système',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Rôle admin créé (ID: {$roleId})\n";
    } else {
        $roleId = $adminRole->id;
        echo "Rôle admin existe (ID: {$roleId})\n";
    }
    
    // Attribuer le rôle admin à l'utilisateur
    DB::table('role_user')->updateOrInsert(
        ['user_id' => $admin->id, 'role_id' => $roleId],
        [
            'user_id' => $admin->id, 
            'role_id' => $roleId,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]
    );
    echo "Rôle admin attribué à l'administrateur\n";
    
    // Créer des permissions de base pour tous les modules
    $modules = ['hr', 'accounting', 'purchases', 'suppliers', 'clients', 'cash', 'inventory', 'users', 'companies', 'agencies'];
    
    foreach ($modules as $module) {
        $permissionSlug = $module . '.access';
        $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
        
        if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'nom' => 'Accès ' . ucfirst($module),
                'slug' => $permissionSlug,
                'module' => $module,
                'description' => 'Permission d\'accès au module ' . $module,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "Permission {$permissionSlug} créée\n";
        } else {
            $permissionId = $permission->id;
            echo "Permission {$permissionSlug} existe\n";
        }
        
        // Attribuer la permission à l'administrateur
        DB::table('user_permissions')->updateOrInsert(
            ['user_id' => $admin->id, 'permission_id' => $permissionId],
            ['user_id' => $admin->id, 'permission_id' => $permissionId]
        );
        echo "Permission {$permissionSlug} attribuée\n";
    }
    
    echo "✅ Administrateur configuré avec tous les accès\n";
} else {
    echo "❌ Administrateur non trouvé\n";
}

// Vérifier les autres utilisateurs
$users = [
    'hr@tpt-h.com' => 'hr',
    'accounting@tpt-h.com' => 'accounting', 
    'purchases@tpt-h.com' => 'purchases',
    'fournisseur@tpt-h.com' => 'suppliers'
];

foreach ($users as $email => $module) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        echo "\nTraitement de {$email} (ID: {$user->id})\n";
        
        // Créer et attribuer la permission pour le module
        $permissionSlug = $module . '.access';
        $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
        
        if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'nom' => 'Accès ' . ucfirst($module),
                'slug' => $permissionSlug,
                'module' => $module,
                'description' => 'Permission d\'accès au module ' . $module,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $permissionId = $permission->id;
        }
        
        DB::table('user_permissions')->updateOrInsert(
            ['user_id' => $user->id, 'permission_id' => $permissionId],
            ['user_id' => $user->id, 'permission_id' => $permissionId]
        );
        echo "✅ Permission {$permissionSlug} attribuée\n";
    }
}

echo "\n✅ Configuration terminée!\n";