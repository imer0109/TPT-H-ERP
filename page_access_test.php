<?php

// Script de test pour accéder aux pages et vérifier leur affichage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

try {
    // Initialiser l'application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "🔍 Test d'accès aux pages de l'application\n";
    echo str_repeat("=", 50) . "\n";
    
    // Créer un utilisateur factice pour les tests
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
            return true;
        }
        
        public function hasRole($role) {
            return true;
        }
    };
    
    // Simuler l'authentification
    $app->bind('auth', function() use ($mockUser) {
        return new class($mockUser) {
            private $user;
            
            public function __construct($user) {
                $this->user = $user;
            }
            
            public function guard($name = null) {
                return new class($this->user) {
                    private $user;
                    
                    public function __construct($user) {
                        $this->user = $user;
                    }
                    
                    public function check() { return true; }
                    public function guest() { return false; }
                    public function user() { return $this->user; }
                    public function id() { return 1; }
                    public function validate(array $credentials = []) { return true; }
                    public function setUser(Illuminate\Contracts\Auth\Authenticatable $user) {}
                    public function hasUser() { return true; }
                    public function authenticate() { return $this->user; }
                };
            }
            public function shouldUse($name) {}
        };
    });
    
    // Pages à tester
    $testPages = [
        '/' => 'Page d\'accueil (Dashboard)',
        '/services' => 'Liste des services',
        '/services/create' => 'Création de service',
        '/companies' => 'Liste des sociétés',
        '/hr/dashboard' => 'Dashboard RH',
        '/accounting/dashboard' => 'Dashboard Comptabilité',
        '/purchases/dashboard' => 'Dashboard Achats'
    ];
    
    foreach ($testPages as $url => $description) {
        echo "\n📄 {$description} ({$url}):\n";
        
        try {
            // Créer une requête simulée
            $request = Illuminate\Http\Request::create($url, 'GET');
            
            // Capturer la sortie
            ob_start();
            
            try {
                $response = $app->handle($request);
                $content = $response->getContent();
                
                echo "  📊 Code HTTP: " . $response->getStatusCode() . "\n";
                echo "  📏 Taille du contenu: " . strlen($content) . " octets\n";
                
                // Vérifier si le contenu est vide
                if (empty($content)) {
                    echo "  ❌ Contenu vide - Page blanche\n";
                } else {
                    echo "  ✅ Contenu généré\n";
                    
                    // Vérifier les éléments clés
                    $checks = [
                        'HTML' => '<html',
                        'Body' => '<body',
                        'Title' => '<title>',
                        'CSS' => 'stylesheet',
                        'JavaScript' => '<script',
                        'Contenu principal' => '@yield(\'content\')' // Pour Blade
                    ];
                    
                    foreach ($checks as $checkName => $pattern) {
                        if (stripos($content, $pattern) !== false) {
                            echo "  ✅ {$checkName} présent\n";
                        } else {
                            echo "  ⚠️  {$checkName} absent\n";
                        }
                    }
                    
                    // Vérifier s'il s'agit d'une redirection
                    if ($response->getStatusCode() == 302) {
                        echo "  🔄 Redirection vers: " . $response->headers->get('Location') . "\n";
                    }
                }
                
            } catch (Exception $e) {
                echo "  💥 Erreur: " . $e->getMessage() . "\n";
            }
            
            ob_end_clean();
            
        } catch (Exception $e) {
            echo "  ❌ Erreur fatale: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ Test terminé\n";

} catch (Exception $e) {
    echo "💥 Erreur fatale: " . $e->getMessage() . "\n";
    echo "Traçage: " . $e->getTraceAsString() . "\n";
}