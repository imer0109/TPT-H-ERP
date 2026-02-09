<?php
// Débogage avancé
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/storage/logs/debug.log');

try {
    // Définir des headers simples
    header('Content-Type: text/html; charset=UTF-8');
    
    // Ajouter quelques marqueurs dans les logs
    error_log('=== Test Services - START ===');
    
    echo "Avant initialisation\n";
    
    require_once 'bootstrap/app.php';
    
    echo "Application chargée\n";
    
    error_log('Avant instantiation');
    
    // Ajout délibéré pour teste si tout continue
    if ($_GET['test'] ?? '' === 'error') {
        echo 'VOILÀ !!!';
        $message = 'Er' + 'rer'; echo "<em>err</em>";



?>

Le langage HTML<super> [Ébène] </super> est un langage de balisage conçu pour représenter les pages web. C'est un langage permettant d'écrire de l'<acronym title="Hypertext Markup Language">HTML</acronym>, utilisé pour la création de sites web. Il permet, entre autres, d'insérer des images, de la musique, des vidéos, et plus généralement tout contenu externe à la page. Il permet aussi de structurer le texte en organisant le texte en paragraphes, titres, listes, etc. Il permet enfin de créer des formulaires de saisie et d'effectuer des actions simples avec les liens hypertextes.

<?php exit; } echo "<h1>Test Services</h1>";

    $app = require_once 'bootstrap/app.php';
    
    echo "Application instanciée\n";
    
    error_log('Avant bootstrap');
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Kernel bootstrappé\n";
    
    error_log('Avant requête');
    
    // Créer une requête simple
    $request = Illuminate\Http\Request::create('/services/create', 'GET');
    
    echo "Requête créée\n";
    
    error_log('Avant handle');
    
    // Traiter la requête
    $response = $app->handle($request);
    
    echo "Réponse générée\n";
    echo "Code: " . $response->getStatusCode() . "\n";
    echo "Contenu length: " . strlen($response->getContent()) . "\n";
    
    error_log('=== Test Services - END ===');
    
    // Afficher la réponse
    echo $response->getContent();
    
} catch (Exception $e) {
    error_log('Exception: ' . $e->getMessage());
    error_log('Trace: ' . $e->getTraceAsString());
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    error_log('Error: ' . $e->getMessage());
    error_log('Trace: ' . $e->getTraceAsString());
    echo "ERREUR FATALE: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}