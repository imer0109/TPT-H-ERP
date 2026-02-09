<?php
// Test de compilation Blade sans serveur HTTP
require_once 'vendor/autoload.php';

// Configuration minimale de Laravel pour Blade
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\View\Factory::class,
    function ($app) {
        $viewFinder = new Illuminate\View\FileViewFinder(
            new Illuminate\Filesystem\Filesystem,
            [__DIR__.'/resources/views']
        );
        
        $filesystem = new Illuminate\Filesystem\Filesystem;
        $viewEngine = new Illuminate\View\Engines\PhpEngine($filesystem);
        $bladeCompiler = new Illuminate\View\Compilers\BladeCompiler(
            $filesystem,
            __DIR__.'/storage/framework/views'
        );
        
        $engineResolver = new Illuminate\View\Engines\EngineResolver;
        $engineResolver->register('blade', function () use ($bladeCompiler) {
            return new Illuminate\View\Engines\CompilerEngine($bladeCompiler);
        });
        $engineResolver->register('php', function () use ($viewEngine) {
            return $viewEngine;
        });
        
        return new Illuminate\View\Factory(
            $engineResolver,
            $viewFinder,
            new Illuminate\Events\Dispatcher
        );
    }
);

// Test de compilation
try {
    $view = $app->make(Illuminate\Contracts\View\Factory::class);
    
    echo "=== Test de compilation Blade ===\n\n";
    
    // Test 1 : Compilation simple
    $rendered = $view->make('test-blade')->render();
    echo "✅ Compilation simple réussie\n";
    echo "Longueur du rendu : " . strlen($rendered) . " caractères\n\n";
    
    // Test 2 : Compilation avec variables
    $rendered = $view->make('test-blade', ['test' => 'Valeur de test'])->render();
    echo "✅ Compilation avec variables réussie\n";
    echo "Contient la variable : " . (strpos($rendered, 'Valeur de test') !== false ? 'OUI' : 'NON') . "\n\n";
    
    // Test 3 : Vérifier les fichiers compilés
    $compiledFiles = glob(__DIR__.'/storage/framework/views/*.php');
    echo "✅ Fichiers compilés créés : " . count($compiledFiles) . "\n";
    foreach ($compiledFiles as $file) {
        echo "  - " . basename($file) . " (" . round(filesize($file)/1024, 2) . " KB)\n";
    }
    
    echo "\n✅ Tous les tests de compilation Blade réussis !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de compilation Blade : " . $e->getMessage() . "\n";
    echo "Ligne : " . $e->getLine() . "\n";
    echo "Fichier : " . $e->getFile() . "\n";
}