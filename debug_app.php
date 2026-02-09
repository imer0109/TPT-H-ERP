<?php

// Activer l'affichage des erreurs pour déboguer
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "🔍 Chargement de l'application Laravel...\n";

require_once 'vendor/autoload.php';

try {
    // Charger l'application Laravel
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "✅ Application Laravel chargée avec succès\n";
    
    // Essayer d'accéder à quelques composants de base
    echo "🔍 Test des composants de base...\n";
    
    // Test de la connexion à la base de données
    $db = $app->make('db');
    $pdo = $db->connection()->getPdo();
    echo "✅ Connexion à la base de données: OK\n";
    
    // Test de l'accès aux routes
    $router = $app->make('router');
    $routes = $router->getRoutes();
    echo "📊 Nombre total de routes: " . count($routes) . "\n";
    
    // Test d'accès à quelques routes spécifiques
    $serviceRoutes = [];
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'services') !== false) {
            $serviceRoutes[] = $uri;
        }
    }
    
    echo "🛣️  Routes contenant 'services': " . count($serviceRoutes) . "\n";
    foreach ($serviceRoutes as $route) {
        echo "  - {$route}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement de l'application: " . $e->getMessage() . "\n";
    echo "Erreur complète: " . $e->getTraceAsString() . "\n";
    
    // Afficher plus d'informations de débogage
    echo "\n🔧 Informations de débogage supplémentaires:\n";
    echo "- PHP Version: " . PHP_VERSION . "\n";
    echo "- OS: " . PHP_OS . "\n";
    echo "- Répertoire courant: " . getcwd() . "\n";
    
    if (file_exists('.env')) {
        echo "- Fichier .env: présent\n";
    } else {
        echo "- Fichier .env: absent\n";
    }
    
    if (file_exists('vendor/autoload.php')) {
        echo "- Autoloader: présent\n";
    } else {
        echo "- Autoloader: absent\n";
    }
    
    exit(1);
}

echo "\n✅ Test terminé avec succès\n";