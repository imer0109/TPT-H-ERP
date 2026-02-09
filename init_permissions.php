<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Initialisation du système de permissions...\n";

// Vérifier et créer les tables si nécessaire
if (!Schema::hasTable('permissions')) {
    echo "Création de la table permissions...\n";
    Schema::create('permissions', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('module');
        $table->string('description')->nullable();
        $table->timestamps();
    });
}

if (!Schema::hasTable('user_permissions')) {
    echo "Création de la table user_permissions...\n";
    Schema::create('user_permissions', function ($table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

if (!Schema::hasTable('role_user')) {
    echo "Création de la table role_user...\n";
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

// Créer les permissions de base
$permissions = [
    ['name' => 'Accès RH', 'slug' => 'hr.access', 'module' => 'hr', 'description' => 'Accès au module Ressources Humaines'],
    ['name' => 'Accès Comptabilité', 'slug' => 'accounting.access', 'module' => 'accounting', 'description' => 'Accès au module Comptabilité'],
    ['name' => 'Accès Achats', 'slug' => 'purchases.access', 'module' => 'purchases', 'description' => 'Accès au module Achats'],
    ['name' => 'Accès Fournisseurs', 'slug' => 'suppliers.access', 'module' => 'suppliers', 'description' => 'Accès au module Fournisseurs'],
    ['name' => 'Accès Clients', 'slug' => 'clients.access', 'module' => 'clients', 'description' => 'Accès au module Clients'],
    ['name' => 'Accès Stock', 'slug' => 'stock.access', 'module' => 'stock', 'description' => 'Accès au module Stock'],
    ['name' => 'Accès Caisse', 'slug' => 'cash.access', 'module' => 'cash', 'description' => 'Accès au module Caisse'],
    ['name' => 'Accès Sociétés', 'slug' => 'companies.access', 'module' => 'companies', 'description' => 'Accès au module Sociétés'],
    ['name' => 'Accès Agences', 'slug' => 'agencies.access', 'module' => 'agencies', 'description' => 'Accès au module Agences'],
    ['name' => 'Accès Utilisateurs', 'slug' => 'users.access', 'module' => 'users', 'description' => 'Accès au module Utilisateurs'],
];

echo "Création des permissions...\n";
foreach ($permissions as $permissionData) {
    try {
        DB::table('permissions')->updateOrInsert(
            ['slug' => $permissionData['slug']],
            $permissionData
        );
        echo "Permission {$permissionData['name']} créée/mise à jour\n";
    } catch (Exception $e) {
        echo "Erreur lors de la création de {$permissionData['name']}: " . $e->getMessage() . "\n";
    }
}

// Assigner des permissions aux utilisateurs existants
$users = [
    'hr@tpt-h.com' => ['hr.access'],
    'accounting@tpt-h.com' => ['accounting.access'],
    'purchases@tpt-h.com' => ['purchases.access'],
    'fournisseur@tpt-h.com' => ['suppliers.access'],
    'admin@tpt-h.com' => ['hr.access', 'accounting.access', 'purchases.access', 'suppliers.access', 'clients.access', 'stock.access', 'cash.access', 'companies.access', 'agencies.access', 'users.access'],
];

echo "Assignation des permissions aux utilisateurs...\n";
foreach ($users as $email => $permissionSlugs) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        foreach ($permissionSlugs as $slug) {
            $permission = DB::table('permissions')->where('slug', $slug)->first();
            if ($permission) {
                try {
                    DB::table('user_permissions')->updateOrInsert(
                        ['user_id' => $user->id, 'permission_id' => $permission->id],
                        ['user_id' => $user->id, 'permission_id' => $permission->id]
                    );
                    echo "Permission {$slug} assignée à {$email}\n";
                } catch (Exception $e) {
                    echo "Erreur lors de l'assignation de {$slug} à {$email}: " . $e->getMessage() . "\n";
                }
            }
        }
    } else {
        echo "Utilisateur {$email} non trouvé\n";
    }
}

echo "Initialisation terminée.\n";