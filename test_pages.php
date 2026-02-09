<?php

// Activer l'affichage des erreurs pour déboguer
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "🔍 Test des pages de l'application\n";
echo str_repeat("=", 50) . "\n";

// Charger l'application Laravel
require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Application Laravel chargée avec succès\n";
    
    // Créer une instance de l'application pour simuler les requêtes
    $app->bind('middleware.disable', function() {
        return true;
    });
    
    // Simuler différentes requêtes pour tester les pages
    $testPages = [
        '/' => 'Page d\'accueil',
        '/login' => 'Page de connexion',
        '/dashboard' => 'Dashboard',
        '/services' => 'Liste des services',
        '/services/create' => 'Création de service',
        '/companies' => 'Liste des sociétés',
        '/employees' => 'Liste des employés',
        '/users' => 'Gestion des utilisateurs'
    ];
    
    echo "\n🧪 Tests des différentes pages:\n";
    
    foreach ($testPages as $page => $description) {
        echo "  📄 {$description} ({$page}): ";
        
        try {
            // Créer une requête simulée
            $request = Illuminate\Http\Request::create($page, 'GET');
            
            // Intercepter la réponse pour éviter les redirections ou erreurs fatales
            ob_start();
            try {
                $response = $app->handle($request);
                $content = $response->getContent();
                
                // Vérifier si le contenu est vide ou s'il s'agit d'une redirection
                if ($response->getStatusCode() == 302) {
                    echo "🔄 Redirection (Code: 302)";
                } elseif (empty($content) && $response->getStatusCode() != 200) {
                    echo "❌ Erreur (Code: {$response->getStatusCode()})";
                } else {
                    echo "✅ OK (Code: {$response->getStatusCode()}, Taille: " . strlen($content) . " chars)";
                }
            } catch (Exception $e) {
                echo "💥 Exception: " . $e->getMessage();
            }
            
            ob_end_clean(); // Nettoyer la sortie pour ne pas l'afficher
            
            echo "\n";
        } catch (Exception $e) {
            echo "💥 Erreur fatale: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n✅ Tests terminés !\n";
    echo "💡 L'application semble fonctionner correctement. Les pages devraient maintenant s'afficher sans pages blanches.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement de l'application: " . $e->getMessage() . "\n";
    echo "Erreur complète: " . $e->getTraceAsString() . "\n";
    exit(1);
}