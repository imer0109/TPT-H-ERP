<?php
/**
 * Test de la logique de redirection des dashboards
 * 
 * Ce script teste si les utilisateurs sont redirigés vers le bon dashboard
 */

require_once 'vendor/autoload.php';

// Initialisation de l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== TEST DE REDIRECTION DES DASHBOARDS ===\n\n";

// Simuler l'authentification pour chaque utilisateur
$testUsers = [
    'admin@tpt-h.com' => 'Administrateur',
    'manager@tpt-h.com' => 'Manager',
    'hr@tpt-h.com' => 'RH',
    'accounting@tpt-h.com' => 'Comptabilité',
    'purchases@tpt-h.com' => 'Achats',
    'agent@tpt-h.com' => 'Agent Opérationnel',
    'viewer@tpt-h.com' => 'Observateur',
    'supervisor@tpt-h.com' => 'Superviseur',
    'fournisseur@tpt-h.com' => 'Fournisseur'
];

foreach ($testUsers as $email => $userType) {
    echo "Test pour {$userType} ({$email}):\n";
    
    $user = User::where('email', $email)->first();
    if ($user) {
        // Simuler l'authentification
        Auth::login($user);
        
        // Appeler la méthode index du DashboardController
        $controller = new \App\Http\Controllers\DashboardController();
        
        try {
            $response = $controller->index();
            
            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                echo "   ✅ Redirection vers: " . $response->getTargetUrl() . "\n";
            } else {
                echo "   ✅ Affichage du dashboard principal\n";
            }
        } catch (Exception $e) {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
        
        // Déconnecter l'utilisateur
        Auth::logout();
    } else {
        echo "   ❌ Utilisateur non trouvé\n";
    }
    echo "\n";
}

echo "=== TEST TERMINÉ ===\n";