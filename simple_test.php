<?php

// Activer l'affichage des erreurs pour déboguer
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "🔍 Test simple de l'application Laravel\n";
echo str_repeat("-", 40) . "\n";

// Test sans charger toute l'application Laravel
echo "1. Test de l'autoload...\n";
if (file_exists('vendor/autoload.php')) {
    echo "✅ Autoloader trouvé\n";
} else {
    echo "❌ Autoloader introuvable\n";
    exit(1);
}

// Test de chargement des classes de base
echo "2. Test de chargement des classes de base...\n";
try {
    require_once 'vendor/autoload.php';
    echo "✅ Classes de base chargées\n";
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement: " . $e->getMessage() . "\n";
    exit(1);
}

// Test de configuration minimale
echo "3. Test de configuration minimale...\n";
try {
    // Charger uniquement la configuration nécessaire
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    echo "✅ Variables d'environnement chargées\n";
} catch (Exception $e) {
    echo "⚠️  Erreur avec dotenv (peut être ignorée si .env est correctement lu): " . $e->getMessage() . "\n";
}

// Test de chargement spécifique d'un modèle sans charger l'application complète
echo "4. Test de chargement d'un modèle simple...\n";
try {
    // Définir les constantes nécessaires
    if (!defined('LARAVEL_START')) {
        define('LARAVEL_START', microtime(true));
    }
    
    // Charger la configuration de base
    $config = require __DIR__.'/config/app.php';
    echo "✅ Config app.php chargée\n";
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement de la config: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Test de base réussi! Le problème pourrait être dans les services spécifiques.\n";
echo "💡 Essayez de lancer la commande: php artisan config:cache\n";
echo "💡 Ou vérifiez les erreurs dans le fichier de log: storage/logs/laravel.log\n";