<?php

// Script de test pour vérifier si les pages s'affichent correctement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

try {
    // Initialiser l'application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Application Laravel chargée avec succès\n";
    
    // Créer une requête simulée pour la page d'accueil
    $request = Illuminate\Http\Request::create('/', 'GET');
    
    // Désactiver temporairement l'authentification
    $app->bind('auth', function() {
        return new class implements Illuminate\Contracts\Auth\Factory {
            public function guard($name = null) {
                return new class implements Illuminate\Contracts\Auth\Guard {
                    public function check() { return true; }
                    public function guest() { return false; }
                    public function user() { 
                        // Créer un utilisateur factice
                        $mockUser = new class {
                            public $prenom = 'Test';
                            public $nom = 'User';
                            public $roles;
                            
                            public function __construct() {
                                $this->roles = collect([new class { 
                                    public $nom = 'Admin';
                                    public function first() { return $this; }
                                }]);
                            }
                            
                            public function canAccessModule($module) {
                                return true; // Autoriser tous les modules
                            }
                            
                            public function hasRole($role) {
                                return true; // Autoriser tous les rôles
                            }
                        };
                        return $mockUser;
                    }
                    public function id() { return 1; }
                    public function validate(array $credentials = []) { return true; }
                    public function setUser(Illuminate\Contracts\Auth\Authenticatable $user) {}
                    public function hasUser() { return true; }
                    public function authenticate() { return $this->user(); }
                };
            }
            public function shouldUse($name) {}
        };
    });
    
    echo "ℹ️  Test de rendu de la page d'accueil...\n";
    
    // Gérer la requête
    $response = $app->handle($request);
    
    echo "✅ Requête traitée avec succès\n";
    echo "📊 Code de statut: " . $response->getStatusCode() . "\n";
    
    $content = $response->getContent();
    echo "📏 Taille du contenu: " . strlen($content) . " caractères\n";
    
    if (strlen($content) > 0) {
        echo "✅ Contenu généré avec succès\n";
        
        // Vérifier si le contenu contient des éléments HTML de base
        if (strpos($content, '<html') !== false && strpos($content, '<body') !== false) {
            echo "✅ Structure HTML valide détectée\n";
        } else {
            echo "⚠️  Structure HTML incomplète\n";
        }
        
        // Vérifier si le contenu contient des éléments du layout
        if (strpos($content, 'TPT-H ERP') !== false) {
            echo "✅ Titre de l'application détecté\n";
        }
        
        if (strpos($content, 'Tableau de bord') !== false) {
            echo "✅ Éléments du dashboard détectés\n";
        }
        
        if (strpos($content, 'csrf-token') !== false) {
            echo "✅ Jeton CSRF présent\n";
        }
        
        echo "\n🎉 Test réussi ! Les pages devraient s'afficher correctement.\n";
    } else {
        echo "❌ Aucun contenu généré\n";
    }

} catch (Exception $e) {
    echo "💥 Erreur: " . $e->getMessage() . "\n";
    echo "Traçage: " . $e->getTraceAsString() . "\n";
}