<?php

// Script de test pour diagnostiquer les erreurs de rendu de vue
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Auth;

try {
    // Initialiser l'application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Application Laravel chargée avec succès\n";
    
    // Créer une requête simulée
    $request = Illuminate\Http\Request::create('/', 'GET');
    
    // Désactiver temporairement l'authentification pour tester le layout
    $app->bind('auth', function() {
        return new class implements Illuminate\Contracts\Auth\Guard {
            public function check() { return false; }
            public function guest() { return true; }
            public function user() { return null; }
            public function id() { return null; }
            public function validate(array $credentials = []) { return false; }
            public function setUser(Illuminate\Contracts\Auth\Authenticatable $user) {}
            public function hasUser() { return false; }
            public function authenticate() { throw new Illuminate\Auth\AuthenticationException(); }
        };
    });
    
    echo "ℹ️  Test de rendu de la vue dashboard.index sans utilisateur authentifié...\n";
    
    // Essayons de créer une instance de la factory de vues
    $viewFactory = $app->make('view');
    
    // Essayons de rendre la vue avec des données factices
    $fakeData = [
        'tresorerieConsolidee' => 1250000,
        'masseSalariale' => 2500000,
        'stockDisponible' => 1500,
        'achatsMensuels' => 850000,
        'session' => null,
        'chartData' => [
            'labels' => ['Jan', 'Fév', 'Mar'],
            'datasets' => []
        ],
        'alerts' => []
    ];
    
    // Remplacer temporairement la méthode auth()->user() pour éviter les erreurs
    $renderer = new class($viewFactory, $fakeData) {
        private $viewFactory;
        private $fakeData;
        
        public function __construct($viewFactory, $fakeData) {
            $this->viewFactory = $viewFactory;
            $this->fakeData = $fakeData;
        }
        
        public function renderView() {
            try {
                // Sauvegarder l'ancien contexte d'authentification
                $oldAuth = app('auth');
                
                // Remplacer avec un mock pour éviter les erreurs
                app()->bind('auth', function() {
                    return new class implements Illuminate\Contracts\Auth\Factory {
                        public function guard($name = null) {
                            return new class implements Illuminate\Contracts\Auth\Guard {
                                public function check() { return true; }
                                public function guest() { return false; }
                                public function user() { 
                                    // Retourner un objet factice avec les propriétés nécessaires
                                    $mockUser = new stdClass();
                                    $mockUser->prenom = 'Test';
                                    $mockUser->nom = 'User';
                                    $mockUser->roles = collect([new class { public $nom = 'Admin'; }]);
                                    
                                    // Ajouter la méthode canAccessModule
                                    $mockUser->canAccessModule = function($module) {
                                        return true; // Autoriser tous les modules pour le test
                                    };
                                    
                                    $mockUser->hasRole = function($role) {
                                        return true; // Autoriser tous les rôles pour le test
                                    };
                                    
                                    return $mockUser;
                                }
                                public function id() { return 1; }
                                public function validate(array $credentials = []) { return true; }
                                public function setUser(Illuminate\Contracts\Auth\Authenticatable $user) {}
                                public function hasUser() { return true; }
                                public function authenticate() { return (object)['prenom' => 'Test', 'nom' => 'User']; }
                            };
                        }
                        public function shouldUse($name) {}
                    };
                });
                
                $content = $this->viewFactory->make('dashboard.index', $this->fakeData)->render();
                echo "✅ Vue dashboard.index rendue avec succès\n";
                echo "📏 Taille du contenu: " . strlen($content) . " caractères\n";
                
                return $content;
            } catch (Exception $e) {
                echo "❌ Erreur lors du rendu de la vue: " . $e->getMessage() . "\n";
                echo "Traçage: " . $e->getTraceAsString() . "\n";
                return false;
            }
        }
    };
    
    $result = $renderer->renderView();
    
    if ($result) {
        echo "🎉 Le problème ne vient pas du rendu de base de la vue\n";
        echo "🔍 Le problème est probablement lié à l'authentification ou à la récupération des données\n";
    }

} catch (Exception $e) {
    echo "💥 Erreur fatale: " . $e->getMessage() . "\n";
    echo "Traçage: " . $e->getTraceAsString() . "\n";
}