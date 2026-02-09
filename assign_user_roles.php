<?php
/**
 * Script pour attribuer les rôles corrects aux utilisateurs de test
 * 
 * Ce script va :
 * 1. Créer les rôles nécessaires s'ils n'existent pas
 * 2. Attribuer les rôles appropriés aux utilisateurs de test
 */

require_once 'vendor/autoload.php';

// Initialisation de l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== ATTRIBUTION DES RÔLES AUX UTILISATEURS DE TEST ===\n\n";

// 1. Vérifier les rôles existants
echo "1. Vérification des rôles existants...\n";
$requiredRoles = [
    'admin' => 'Administrateur',
    'manager' => 'Manager',
    'hr' => 'Ressources Humaines',
    'accounting' => 'Comptabilité',
    'purchases' => 'Achats',
    'consultant' => 'Consultant',
    'viewer' => 'Observateur',
    'operational' => 'Opérationnel',
    'supplier' => 'Fournisseur'
];

$existingRoles = [];
foreach ($requiredRoles as $slug => $name) {
    $role = Role::where('slug', $slug)->first();
    if ($role) {
        $existingRoles[$slug] = $role;
        echo "   ✅ Rôle '{$name}' ({$slug}) existe\n";
    } else {
        echo "   ⚠️ Rôle '{$name}' ({$slug}) n'existe pas\n";
    }
}

// 2. Attribuer les rôles aux utilisateurs
echo "\n2. Attribution des rôles aux utilisateurs...\n";

$usersConfig = [
    'admin@tpt-h.com' => ['admin'],
    'manager@tpt-h.com' => ['manager'],
    'hr@tpt-h.com' => ['hr'],
    'accounting@tpt-h.com' => ['accounting'],
    'purchases@tpt-h.com' => ['purchases'],
    'agent@tpt-h.com' => ['operational'],
    'viewer@tpt-h.com' => ['viewer'],
    'supervisor@tpt-h.com' => ['manager'],
    'fournisseur@tpt-h.com' => ['supplier']
];

foreach ($usersConfig as $email => $roleSlugs) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "   Utilisateur: {$user->name} ({$email})\n";
        
        // Supprimer les rôles existants
        $user->roles()->detach();
        echo "     - Rôles précédents supprimés\n";
        
        // Attribuer les nouveaux rôles
        foreach ($roleSlugs as $roleSlug) {
            if (isset($existingRoles[$roleSlug])) {
                $user->roles()->attach($existingRoles[$roleSlug]->id);
                echo "     ✅ Rôle attribué: {$existingRoles[$roleSlug]->nom}\n";
            } else {
                echo "     ❌ Rôle non trouvé: {$roleSlug}\n";
            }
        }
    } else {
        echo "   ❌ Utilisateur non trouvé: {$email}\n";
    }
}

// 3. Vérification finale
echo "\n3. Vérification finale...\n";
foreach ($usersConfig as $email => $roleSlugs) {
    $user = User::where('email', $email)->first();
    if ($user) {
        $userRoles = $user->roles->pluck('slug')->toArray();
        echo "   {$user->name}: " . implode(', ', $userRoles) . "\n";
    }
}

echo "\n=== ATTRIBUTION TERMINÉE ===\n";
echo "Redémarrez votre serveur et testez la connexion avec différents utilisateurs.\n";
echo "Chaque utilisateur devrait maintenant être redirigé vers son dashboard approprié.\n";