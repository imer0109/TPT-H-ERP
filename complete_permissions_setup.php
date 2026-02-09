<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Configuration complète des permissions, rôles et middlewares\n";
echo str_repeat("=", 60) . "\n";

// 1. Vérifier et créer les tables nécessaires
echo "📋 Vérification des tables...\n";

$tablesToCheck = ['permissions', 'roles', 'user_permissions', 'role_user', 'permission_role'];
foreach ($tablesToCheck as $table) {
    if (Schema::hasTable($table)) {
        echo "  ✅ Table {$table} existe\n";
    } else {
        echo "  ❌ Table {$table} manquante\n";
    }
}

// 2. Créer les rôles de base
echo "\n👥 Création des rôles...\n";

$roles = [
    [
        'slug' => 'administrateur',
        'nom' => 'Administrateur Système',
        'description' => 'Accès complet à tous les modules',
        'color' => '#ef4444'
    ],
    [
        'slug' => 'admin',
        'nom' => 'Administrateur',
        'description' => 'Administrateur avec droits étendus',
        'color' => '#dc2626'
    ],
    [
        'slug' => 'manager',
        'nom' => 'Gestionnaire',
        'description' => 'Gestion des opérations principales',
        'color' => '#f59e0b'
    ],
    [
        'slug' => 'supervisor',
        'nom' => 'Superviseur',
        'description' => 'Supervision et validation',
        'color' => '#8b5cf6'
    ],
    [
        'slug' => 'hr',
        'nom' => 'Ressources Humaines',
        'description' => 'Gestion des ressources humaines',
        'color' => '#f59e0b'
    ],
    [
        'slug' => 'accounting',
        'nom' => 'Comptabilité',
        'description' => 'Gestion comptable et financière',
        'color' => '#10b981'
    ],
    [
        'slug' => 'purchases',
        'nom' => 'Achats',
        'description' => 'Gestion des achats et fournisseurs',
        'color' => '#8b5cf6'
    ],
    [
        'slug' => 'suppliers',
        'nom' => 'Fournisseurs',
        'description' => 'Accès espace fournisseur',
        'color' => '#dc2626'
    ],
    [
        'slug' => 'operational',
        'nom' => 'Agent Opérationnel',
        'description' => 'Opérations quotidiennes',
        'color' => '#3b82f6'
    ],
    [
        'slug' => 'viewer',
        'nom' => 'Consultant',
        'description' => 'Accès en lecture seule',
        'color' => '#6b7280'
    ]
];

foreach ($roles as $roleData) {
    $existingRole = DB::table('roles')->where('slug', $roleData['slug'])->first();
    if (!$existingRole) {
        $roleId = DB::table('roles')->insertGetId([
            'nom' => $roleData['nom'],
            'slug' => $roleData['slug'],
            'description' => $roleData['description'],
            'color' => $roleData['color'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "  ✅ Rôle {$roleData['nom']} créé (ID: {$roleId})\n";
    } else {
        echo "  ℹ️  Rôle {$roleData['nom']} existe déjà\n";
    }
}

// 3. Créer les permissions pour chaque module
echo "\n🔐 Création des permissions par module...\n";

$modules = [
    'hr' => [
        'name' => 'Ressources Humaines',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports']
    ],
    'accounting' => [
        'name' => 'Comptabilité',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports', 'balance', 'ledger']
    ],
    'purchases' => [
        'name' => 'Achats',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports', 'orders', 'suppliers']
    ],
    'suppliers' => [
        'name' => 'Fournisseurs',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports', 'deliveries', 'invoices']
    ],
    'clients' => [
        'name' => 'Clients',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports']
    ],
    'cash' => [
        'name' => 'Caisse',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports', 'transactions']
    ],
    'inventory' => [
        'name' => 'Stock',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports', 'movements', 'transfers']
    ],
    'users' => [
        'name' => 'Utilisateurs',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard', 'roles', 'permissions']
    ],
    'companies' => [
        'name' => 'Sociétés',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard']
    ],
    'agencies' => [
        'name' => 'Agences',
        'permissions' => ['view', 'create', 'edit', 'delete', 'dashboard']
    ]
];

foreach ($modules as $moduleSlug => $moduleData) {
    echo "  Module {$moduleData['name']} ({$moduleSlug}):\n";
    
    foreach ($moduleData['permissions'] as $action) {
        $permissionSlug = "{$moduleSlug}.{$action}";
        $permissionName = ucfirst($action) . ' ' . $moduleData['name'];
        
        $existingPermission = DB::table('permissions')->where('slug', $permissionSlug)->first();
        if (!$existingPermission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'nom' => $permissionName,
                'slug' => $permissionSlug,
                'module' => $moduleSlug,
                'description' => "Permission {$permissionName}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "    ✅ {$permissionName} créée\n";
        } else {
            echo "    ℹ️  {$permissionName} existe déjà\n";
        }
    }
}

// 4. Attribuer les rôles aux utilisateurs
echo "\n👤 Attribution des rôles aux utilisateurs...\n";

$userRoles = [
    'admin@tpt-h.com' => 'administrateur',
    'manager@tpt-h.com' => 'manager',
    'supervisor@tpt-h.com' => 'supervisor',
    'hr@tpt-h.com' => 'hr',
    'accounting@tpt-h.com' => 'accounting',
    'purchases@tpt-h.com' => 'purchases',
    'fournisseur@tpt-h.com' => 'suppliers',
    'agent@tpt-h.com' => 'operational',
    'viewer@tpt-h.com' => 'viewer'
];

foreach ($userRoles as $email => $roleSlug) {
    $user = DB::table('users')->where('email', $email)->first();
    $role = DB::table('roles')->where('slug', $roleSlug)->first();
    
    if ($user && $role) {
        // Vérifier si l'attribution existe déjà
        $existingAssignment = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', $role->id)
            ->first();
            
        if (!$existingAssignment) {
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "  ✅ {$email} → {$role->nom}\n";
        } else {
            echo "  ℹ️  {$email} a déjà le rôle {$role->nom}\n";
        }
    } else {
        if (!$user) {
            echo "  ❌ Utilisateur {$email} non trouvé\n";
        }
        if (!$role) {
            echo "  ❌ Rôle {$roleSlug} non trouvé\n";
        }
    }
}

// 5. Attribuer les permissions spécifiques par rôle
echo "\n🔑 Attribution des permissions par rôle...\n";

$rolePermissions = [
    'administrateur' => [
        'hr.view', 'hr.create', 'hr.edit', 'hr.delete', 'hr.dashboard', 'hr.reports',
        'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.delete', 'accounting.dashboard', 'accounting.reports', 'accounting.balance', 'accounting.ledger',
        'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.delete', 'purchases.dashboard', 'purchases.reports', 'purchases.orders', 'purchases.suppliers',
        'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.delete', 'suppliers.dashboard', 'suppliers.reports', 'suppliers.deliveries', 'suppliers.invoices',
        'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.dashboard', 'clients.reports',
        'cash.view', 'cash.create', 'cash.edit', 'cash.delete', 'cash.dashboard', 'cash.reports', 'cash.transactions',
        'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete', 'inventory.dashboard', 'inventory.reports', 'inventory.movements', 'inventory.transfers',
        'users.view', 'users.create', 'users.edit', 'users.delete', 'users.dashboard', 'users.roles', 'users.permissions',
        'companies.view', 'companies.create', 'companies.edit', 'companies.delete', 'companies.dashboard',
        'agencies.view', 'agencies.create', 'agencies.edit', 'agencies.delete', 'agencies.dashboard'
    ],
    'admin' => [
        'hr.view', 'hr.create', 'hr.edit', 'hr.dashboard', 'hr.reports',
        'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.dashboard', 'accounting.reports',
        'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.dashboard', 'purchases.reports',
        'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.dashboard', 'suppliers.reports',
        'clients.view', 'clients.create', 'clients.edit', 'clients.dashboard', 'clients.reports',
        'cash.view', 'cash.create', 'cash.edit', 'cash.dashboard', 'cash.reports',
        'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.dashboard', 'inventory.reports',
        'users.view', 'users.create', 'users.edit', 'users.dashboard', 'users.roles'
    ],
    'manager' => [
        'hr.view', 'hr.create', 'hr.edit', 'hr.dashboard',
        'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.dashboard',
        'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.dashboard',
        'clients.view', 'clients.create', 'clients.edit', 'clients.dashboard',
        'cash.view', 'cash.create', 'cash.edit', 'cash.dashboard',
        'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.dashboard'
    ],
    'hr' => [
        'hr.view', 'hr.create', 'hr.edit', 'hr.dashboard', 'hr.reports'
    ],
    'accounting' => [
        'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.dashboard', 'accounting.reports', 'accounting.balance'
    ],
    'purchases' => [
        'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.dashboard', 'purchases.orders',
        'suppliers.view', 'suppliers.dashboard'
    ],
    'suppliers' => [
        'suppliers.view', 'suppliers.dashboard', 'suppliers.deliveries'
    ],
    'operational' => [
        'clients.view', 'clients.dashboard',
        'cash.view', 'cash.dashboard'
    ],
    'viewer' => [
        'clients.view', 'clients.dashboard'
    ]
];

foreach ($rolePermissions as $roleSlug => $permissions) {
    $role = DB::table('roles')->where('slug', $roleSlug)->first();
    if ($role) {
        echo "  Rôle {$role->nom}:\n";
        
        foreach ($permissions as $permissionSlug) {
            $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
            if ($permission) {
                // Vérifier si l'attribution existe déjà
                $existingPermissionRole = DB::table('permission_role')
                    ->where('permission_id', $permission->id)
                    ->where('role_id', $role->id)
                    ->first();
                    
                if (!$existingPermissionRole) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    echo "    ✅ {$permission->nom} attribuée\n";
                } else {
                    echo "    ℹ️  {$permission->nom} déjà attribuée\n";
                }
            } else {
                echo "    ❌ Permission {$permissionSlug} non trouvée\n";
            }
        }
    }
}

// 6. Vérification finale
echo "\n✅ Configuration terminée!\n";
echo str_repeat("=", 60) . "\n";

echo "\n📊 Résumé des accès par utilisateur:\n";
foreach ($userRoles as $email => $roleSlug) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        $accessibleModules = DB::table('user_permissions')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->where('user_permissions.user_id', $user->id)
            ->pluck('permissions.module')
            ->unique()
            ->toArray();
            
        $role = DB::table('roles')->where('slug', $roleSlug)->first();
        echo "{$email} ({$role->nom}): " . implode(', ', $accessibleModules) . "\n";
    }
}

echo "\n🔧 Pour tester:\n";
echo "- Admin: admin@tpt-h.com / password (tous les modules)\n";
echo "- RH: hr@tpt-h.com / password (module RH)\n";
echo "- Comptabilité: accounting@tpt-h.com / password (module comptabilité)\n";
echo "- Achats: purchases@tpt-h.com / password (modules achats et fournisseurs)\n";