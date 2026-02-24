<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Initialisation complète du système de permissions\n";
echo str_repeat("=", 60) . "\n";

// Vérifier et créer les tables si nécessaire
echo "📋 Vérification des tables...\n";

// Créer la table roles si elle n'existe pas
if (!Schema::hasTable('roles')) {
    echo "  → Création de la table roles...\n";
    Schema::create('roles', function ($table) {
        $table->id();
        $table->string('nom');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->string('color')->nullable();
        $table->boolean('is_system')->default(false);
        $table->boolean('is_temporary')->default(false);
        $table->timestamp('expires_at')->nullable();
        $table->foreignId('company_id')->nullable()->constrained('companies');
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->foreignId('updated_by')->nullable()->constrained('users');
        $table->timestamps();
        $table->softDeletes();
    });
}

// Créer la table permissions si elle n'existe pas
if (!Schema::hasTable('permissions')) {
    echo "  → Création de la table permissions...\n";
    Schema::create('permissions', function ($table) {
        $table->id();
        $table->string('nom');
        $table->string('slug')->unique();
        $table->string('module');
        $table->string('resource')->nullable();
        $table->string('action')->default('view');
        $table->text('description')->nullable();
        $table->boolean('is_system')->default(false);
        $table->boolean('requires_validation')->default(false);
        $table->integer('validation_level')->default(1);
        $table->timestamps();
    });
}

// Créer la table permission_role si elle n'existe pas
if (!Schema::hasTable('permission_role')) {
    echo "  → Création de la table permission_role...\n";
    Schema::create('permission_role', function ($table) {
        $table->id();
        $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        $table->foreignId('role_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        
        // Index pour les performances
        $table->index(['permission_id', 'role_id']);
    });
}

// Créer la table role_user si elle n'existe pas
if (!Schema::hasTable('role_user')) {
    echo "  → Création de la table role_user...\n";
    Schema::create('role_user', function ($table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('role_id')->constrained()->onDelete('cascade');
        $table->foreignId('assigned_by')->nullable()->constrained('users');
        $table->timestamp('assigned_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
    });
}

// Créer la table user_permissions si elle n'existe pas
if (!Schema::hasTable('user_permissions')) {
    echo "  → Création de la table user_permissions...\n";
    Schema::create('user_permissions', function ($table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        $table->foreignId('granted_by')->nullable()->constrained('users');
        $table->timestamp('granted_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
        
        // Index pour les performances
        $table->index(['user_id', 'permission_id']);
    });
}

echo "✅ Tables vérifiées/créées\n\n";

// Créer les rôles de base
echo "🎭 Création des rôles de base...\n";
$roles = [
    [
        'nom' => 'Administrateur Système',
        'slug' => 'administrateur',
        'description' => 'Administrateur avec accès complet à toutes les fonctionnalités',
        'color' => '#ef4444',
        'is_system' => true
    ],
    [
        'nom' => 'Gestionnaire',
        'slug' => 'manager',
        'description' => 'Gestionnaire avec accès aux fonctions de supervision',
        'color' => '#3b82f6',
        'is_system' => true
    ],
    [
        'nom' => 'Superviseur',
        'slug' => 'supervisor',
        'description' => 'Superviseur avec accès limité aux données de son équipe',
        'color' => '#10b981',
        'is_system' => true
    ],
    [
        'nom' => 'Agent Opérationnel',
        'slug' => 'operational',
        'description' => 'Agent opérationnel avec accès aux fonctions de base',
        'color' => '#f59e0b',
        'is_system' => true
    ],
    [
        'nom' => 'Consultant',
        'slug' => 'viewer',
        'description' => 'Consultant avec accès en lecture seule',
        'color' => '#6b7280',
        'is_system' => true
    ],
    [
        'nom' => 'Ressources Humaines',
        'slug' => 'hr',
        'description' => 'Responsable des ressources humaines',
        'color' => '#8b5cf6',
        'is_system' => true
    ],
    [
        'nom' => 'Comptabilité',
        'slug' => 'accounting',
        'description' => 'Responsable de la comptabilité',
        'color' => '#06b6d4',
        'is_system' => true
    ],
    [
        'nom' => 'Achats',
        'slug' => 'purchases',
        'description' => 'Responsable des achats',
        'color' => '#ec4899',
        'is_system' => true
    ],
    [
        'nom' => 'Fournisseur',
        'slug' => 'supplier',
        'description' => 'Accès à l\'espace fournisseur',
        'color' => '#dc2626',
        'is_system' => true
    ]
];

foreach ($roles as $roleData) {
    $existing = DB::table('roles')->where('slug', $roleData['slug'])->first();
    if (!$existing) {
        DB::table('roles')->insert($roleData);
        echo "  ✅ {$roleData['nom']} créé\n";
    } else {
        echo "  🔁 {$roleData['nom']} existe déjà\n";
    }
}

echo "\n";

// Créer les permissions de base
echo "🔑 Création des permissions de base...\n";
$modules = [
    'users' => ['view', 'create', 'edit', 'delete', 'export'],
    'roles' => ['view', 'create', 'edit', 'delete'],
    'permissions' => ['view', 'create', 'edit', 'delete'],
    'hr' => ['view', 'create', 'edit', 'delete', 'dashboard'],
    'accounting' => ['view', 'create', 'edit', 'delete', 'dashboard', 'reports'],
    'purchases' => ['view', 'create', 'edit', 'delete', 'dashboard', 'orders'],
    'suppliers' => ['view', 'create', 'edit', 'delete', 'dashboard'],
    'clients' => ['view', 'create', 'edit', 'delete', 'dashboard'],
    'inventory' => ['view', 'create', 'edit', 'delete', 'dashboard'],
    'cash' => ['view', 'create', 'edit', 'delete', 'dashboard'],
    'companies' => ['view', 'create', 'edit', 'delete'],
    'agencies' => ['view', 'create', 'edit', 'delete']
];

$permissionsCreated = 0;
foreach ($modules as $module => $actions) {
    foreach ($actions as $action) {
        $permissionSlug = "{$module}.{$action}";
        $permissionName = ucfirst($action) . ' ' . ucfirst($module);
        
        $existing = DB::table('permissions')->where('slug', $permissionSlug)->first();
        if (!$existing) {
            DB::table('permissions')->insert([
                'nom' => $permissionName,
                'slug' => $permissionSlug,
                'module' => $module,
                'resource' => $module,
                'action' => $action,
                'description' => "Permission to {$action} {$module}",
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $permissionsCreated++;
            echo "  ✅ {$permissionName} ({$permissionSlug})\n";
        } else {
            echo "  🔁 {$permissionName} existe déjà\n";
        }
    }
}

echo "Total permissions créées: {$permissionsCreated}\n\n";

// Assigner les permissions aux rôles
echo "🔗 Attribution des permissions aux rôles...\n";

$rolePermissions = [
    'administrateur' => 'ALL', // Toutes les permissions
    'manager' => [
        'users.view', 'users.create', 'users.edit',
        'roles.view', 'roles.create', 'roles.edit',
        'permissions.view',
        'hr.view', 'hr.create', 'hr.edit', 'hr.dashboard',
        'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.dashboard',
        'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.dashboard',
        'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.dashboard',
        'clients.view', 'clients.create', 'clients.edit', 'clients.dashboard',
        'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.dashboard',
        'cash.view', 'cash.create', 'cash.edit', 'cash.dashboard',
        'companies.view', 'companies.create', 'companies.edit',
        'agencies.view', 'agencies.create', 'agencies.edit'
    ],
    'hr' => [
        'users.view', 'users.create', 'users.edit',
        'roles.view', 'permissions.view',
        'hr.view', 'hr.create', 'hr.edit', 'hr.dashboard'
    ],
    'accounting' => [
        'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.dashboard', 'accounting.reports',
        'cash.view', 'cash.create', 'cash.edit', 'cash.dashboard'
    ],
    'purchases' => [
        'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.dashboard', 'purchases.orders',
        'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.dashboard',
        'inventory.view', 'inventory.dashboard'
    ],
    'supervisor' => [
        'users.view',
        'roles.view',
        'hr.view', 'hr.dashboard',
        'accounting.view', 'accounting.dashboard',
        'purchases.view', 'purchases.dashboard',
        'suppliers.view', 'suppliers.dashboard',
        'clients.view', 'clients.dashboard',
        'inventory.view', 'inventory.dashboard',
        'cash.view', 'cash.dashboard'
    ],
    'operational' => [
        'users.view',
        'hr.view', 'hr.dashboard',
        'clients.view', 'clients.dashboard',
        'inventory.view', 'inventory.dashboard'
    ],
    'viewer' => [
        'users.view',
        'hr.view', 'hr.dashboard',
        'accounting.view', 'accounting.dashboard',
        'purchases.view', 'purchases.dashboard',
        'suppliers.view', 'suppliers.dashboard',
        'clients.view', 'clients.dashboard',
        'inventory.view', 'inventory.dashboard',
        'cash.view', 'cash.dashboard'
    ]
];

foreach ($rolePermissions as $roleSlug => $perms) {
    $role = DB::table('roles')->where('slug', $roleSlug)->first();
    if ($role) {
        echo "  Attribution pour {$role->nom}:\n";
        
        if ($perms === 'ALL') {
            // Attribuer toutes les permissions
            $allPermissions = DB::table('permissions')->get();
            foreach ($allPermissions as $permission) {
                $existing = DB::table('permission_role')
                    ->where('role_id', $role->id)
                    ->where('permission_id', $permission->id)
                    ->first();
                
                if (!$existing) {
                    DB::table('permission_role')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            echo "    ✅ Toutes les permissions attribuées\n";
        } else {
            foreach ($perms as $permissionSlug) {
                $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
                if ($permission) {
                    $existing = DB::table('permission_role')
                        ->where('role_id', $role->id)
                        ->where('permission_id', $permission->id)
                        ->first();
                    
                    if (!$existing) {
                        DB::table('permission_role')->insert([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        echo "    ✅ {$permission->nom} attribuée\n";
                    } else {
                        echo "    🔁 {$permission->nom} déjà attribuée\n";
                    }
                } else {
                    echo "    ⚠️  Permission {$permissionSlug} non trouvée\n";
                }
            }
        }
    }
}

echo "\n✅ Initialisation terminée!\n";
echo "\n📊 Résumé:\n";
echo "  - Rôles: " . DB::table('roles')->count() . "\n";
echo "  - Permissions: " . DB::table('permissions')->count() . "\n";
echo "  - Associations rôle-permission: " . DB::table('permission_role')->count() . "\n";