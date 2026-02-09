<?php
/**
 * Script de diagnostic et correction du problème de redirection de dashboard
 * 
 * Ce script va :
 * 1. Diagnostiquer les rôles et permissions des utilisateurs
 * 2. Corriger la logique de redirection
 * 3. S'assurer que chaque utilisateur a le bon dashboard
 */

require_once 'vendor/autoload.php';

// Initialisation de l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== DIAGNOSTIC ET CORRECTION DU PROBLÈME DE DASHBOARD ===\n\n";

// 1. Vérifier les rôles existants
echo "1. Rôles existants :\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "   - {$role->nom} ({$role->slug})\n";
}
echo "\n";

// 2. Vérifier les utilisateurs de test
echo "2. Utilisateurs de test et leurs rôles :\n";
$testUsers = [
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

foreach ($testUsers as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "   {$user->name} ({$user->email}) :\n";
        $userRoles = $user->roles->pluck('nom')->toArray();
        if (!empty($userRoles)) {
            echo "     Rôles : " . implode(', ', $userRoles) . "\n";
        } else {
            echo "     Aucun rôle attribué\n";
        }
        
        // Vérifier les modules accessibles
        $modules = [];
        if ($user->canAccessModule('hr')) $modules[] = 'HR';
        if ($user->canAccessModule('accounting')) $modules[] = 'Comptabilité';
        if ($user->canAccessModule('purchases')) $modules[] = 'Achats';
        if ($user->canAccessModule('cash')) $modules[] = 'Caisse';
        if ($user->canAccessModule('clients')) $modules[] = 'Clients';
        if ($user->canAccessModule('suppliers')) $modules[] = 'Fournisseurs';
        
        if (!empty($modules)) {
            echo "     Modules : " . implode(', ', $modules) . "\n";
        }
        echo "\n";
    }
}

// 3. Analyser la logique de redirection
echo "3. Analyse de la logique de redirection actuelle :\n";
echo "   La logique actuelle vérifie dans cet ordre :\n";
echo "   1. Rôle 'hr' ou module 'hr' → hr.dashboard\n";
echo "   2. Rôle 'accounting' ou module 'accounting' → accounting.dashboard\n";
echo "   3. Rôle 'purchases' ou module 'purchases' → purchases.dashboard\n";
echo "   4. Rôle 'consultant' ou 'viewer' → viewer.dashboard\n";
echo "   5. Rôle 'operational' ou 'agent_operationnel' → operational.dashboard\n";
echo "   6. Rôle 'supplier' → supplier.portal.index\n";
echo "   7. Module 'suppliers' → fournisseurs.dashboard\n";
echo "   8. Module 'clients' → clients.dashboard\n";
echo "   9. Module 'cash' → cash.dashboard\n";
echo "   10. Sinon → dashboard principal\n\n";

// 4. Solution proposée
echo "4. Solution proposée :\n";
echo "   Réorganiser la logique de redirection pour donner la priorité\n";
echo "   aux rôles spécifiques plutôt qu'aux modules généraux.\n\n";

// 5. Appliquer la correction
echo "5. Application de la correction...\n";

// Lire le contrôleur Dashboard
$controllerPath = 'app/Http/Controllers/DashboardController.php';
$content = file_get_contents($controllerPath);

// Sauvegarder l'original
$backupPath = 'app/Http/Controllers/DashboardController.php.backup';
copy($controllerPath, $backupPath);
echo "   Sauvegarde créée : {$backupPath}\n";

// Nouvelle logique de redirection (priorité aux rôles spécifiques)
$newLogic = '
        $user = Auth::user();

        // Priorité aux rôles spécifiques
        if ($user->hasRole(\'admin\') || $user->hasRole(\'administrateur\') || $user->hasRole(\'manager\')) {
            // Administrateurs et managers gardent le dashboard principal
            // Continuer vers le dashboard principal
        }
        elseif ($user->hasRole(\'hr\')) {
            return redirect()->route(\'hr.dashboard\');
        }
        elseif ($user->hasRole(\'accounting\')) {
            return redirect()->route(\'accounting.dashboard\');
        }
        elseif ($user->hasRole(\'purchases\')) {
            return redirect()->route(\'purchases.dashboard\');
        }
        elseif ($user->hasRole(\'consultant\') || $user->hasRole(\'viewer\')) {
            return redirect()->route(\'viewer.dashboard\');
        }
        elseif ($user->hasRole(\'operational\') || $user->hasRole(\'agent_operationnel\')) {
            return redirect()->route(\'operational.dashboard\');
        }
        elseif ($user->hasRole(\'supplier\')) {
            return redirect()->route(\'supplier.portal.index\');
        }
        // Vérifier les modules seulement si aucun rôle spécifique
        elseif ($user->canAccessModule(\'hr\')) {
            return redirect()->route(\'hr.dashboard\');
        }
        elseif ($user->canAccessModule(\'accounting\')) {
            return redirect()->route(\'accounting.dashboard\');
        }
        elseif ($user->canAccessModule(\'purchases\')) {
            return redirect()->route(\'purchases.dashboard\');
        }
        elseif ($user->canAccessModule(\'suppliers\')) {
            return redirect()->route(\'fournisseurs.dashboard\');
        }
        elseif ($user->canAccessModule(\'clients\')) {
            return redirect()->route(\'clients.dashboard\');
        }
        elseif ($user->canAccessModule(\'cash\')) {
            return redirect()->route(\'cash.dashboard\');
        }

        if (
            !$user->canAccessModule(\'dashboard\')
            && !$user->hasRole(\'administrateur\')
            && !$user->hasRole(\'admin\')
            && !$user->hasRole(\'manager\')
        ) {
            abort(403, \'Accès non autorisé\');
        }
';

// Remplacer la logique existante
$pattern = '/public function index\(\)\s*\{\s*\$user = Auth::user\(\);\s*[^}]*\n\s*if\s*\(\s*\$user->canAccessModule\(\'dashboard\'\)\s*\|\|\s*\$user->hasRole\(\'administrateur\'\)\s*\|\|\s*\$user->hasRole\(\'admin\'\)\s*\|\|\s*\$user->hasRole\(\'manager\'\)\s*\)\s*\{\s*[^}]*\}/s';
$replacement = 'public function index() {' . $newLogic;

if (preg_match($pattern, $content)) {
    $newContent = preg_replace($pattern, 'public function index() {' . $newLogic . "\n        // Trésorerie consolidée", $content);
    file_put_contents($controllerPath, $newContent);
    echo "   ✅ Logique de redirection corrigée\n";
} else {
    echo "   ⚠️ Impossible de trouver le pattern exact, modification manuelle nécessaire\n";
}

// 6. Test de la correction
echo "\n6. Test de la correction :\n";
echo "   Redémarrez votre serveur et testez la connexion avec différents utilisateurs.\n";
echo "   Chaque utilisateur devrait maintenant être redirigé vers son dashboard approprié.\n\n";

echo "=== CORRECTION TERMINÉE ===\n";
echo "Si le problème persiste, vérifiez que les rôles sont correctement attribués aux utilisateurs.\n";