<?php
/**
 * Script de réparation du problème Blade
 * 
 * Ce script corrige les problèmes de compilation Blade en :
 * 1. Nettoyant tous les caches
 * 2. Vérifiant les permissions
 * 3. Réinitialisant la configuration Blade
 * 4. Testant la compilation
 */

require_once 'vendor/autoload.php';

// Initialisation de l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SCRIPT DE RÉPARATION BLADE ===\n\n";

// 1. Nettoyage des caches
echo "1. Nettoyage des caches...\n";
$commands = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan clear-compiled'
];

foreach ($commands as $command) {
    echo "  Exécution: $command\n";
    exec($command, $output, $returnCode);
    if ($returnCode === 0) {
        echo "  ✅ OK\n";
    } else {
        echo "  ❌ Erreur\n";
    }
}

// 2. Vérification des permissions
echo "\n2. Vérification des permissions...\n";
$directories = [
    'storage/framework/cache',
    'storage/framework/views',
    'storage/framework/sessions',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "  Création du dossier: $dir\n";
        mkdir($dir, 0755, true);
    }
    
    // Vérification des permissions
    if (is_writable($dir)) {
        echo "  ✅ $dir : Permissions OK\n";
    } else {
        echo "  ❌ $dir : Problème de permissions\n";
        // Tentative de correction sur Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo "    Tentative de correction des permissions Windows...\n";
            exec("icacls \"$dir\" /grant \"Utilisateurs:(OI)(CI)F\"", $output, $returnCode);
            if ($returnCode === 0) {
                echo "    ✅ Permissions corrigées\n";
            }
        }
    }
}

// 3. Test de compilation Blade
echo "\n3. Test de compilation Blade...\n";
try {
    // Test simple de compilation
    $testView = '
    <!DOCTYPE html>
    <html>
    <head><title>Test</title></head>
    <body>
        <h1>Test Blade</h1>
        <p>Date: {{ now()->format("Y-m-d H:i:s") }}</p>
        @if(isset($variable))
            <p>Variable: {{ $variable }}</p>
        @endif
    </body>
    </html>';
    
    file_put_contents('resources/views/temp-test.blade.php', $testView);
    
    // Test de rendu
    $rendered = \Illuminate\Support\Facades\View::make('temp-test', ['variable' => 'Test réussi'])->render();
    
    if (strlen($rendered) > 0 && strpos($rendered, 'Test réussi') !== false) {
        echo "  ✅ Compilation Blade fonctionnelle\n";
        echo "  ✅ Variables fonctionnelles\n";
        echo "  ✅ Directives Blade fonctionnelles\n";
    } else {
        echo "  ❌ Problème de compilation\n";
    }
    
    // Nettoyage
    unlink('resources/views/temp-test.blade.php');
    
} catch (Exception $e) {
    echo "  ❌ Erreur de compilation: " . $e->getMessage() . "\n";
}

// 4. Réinitialisation des caches optimisés
echo "\n4. Réinitialisation des caches optimisés...\n";
$optimizeCommands = [
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
    'php artisan event:cache'
];

foreach ($optimizeCommands as $command) {
    echo "  Exécution: $command\n";
    exec($command, $output, $returnCode);
    if ($returnCode === 0) {
        echo "  ✅ OK\n";
    } else {
        echo "  ❌ Erreur\n";
    }
}

// 5. Test final
echo "\n5. Test final...\n";
exec('php artisan serve --port=8001', $output, $returnCode);
if ($returnCode === 0) {
    echo "  ✅ Serveur démarré sur le port 8001\n";
    echo "  ✅ Testez: http://localhost:8001/blade-test-page\n";
} else {
    echo "  ⚠️ Impossible de démarrer le serveur de test\n";
}

echo "\n=== RÉPARATION TERMINÉE ===\n";
echo "Si les tests sont tous positifs, Blade devrait fonctionner correctement.\n";
echo "Redémarrez votre serveur principal pour appliquer les changements.\n";