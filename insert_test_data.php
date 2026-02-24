<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Configuration de la base de données
$config = require 'config/database.php';
$connection = $config['connections']['mysql'];

try {
    $pdo = new PDO(
        "mysql:host={$connection['host']};dbname={$connection['database']};charset={$connection['charset']}",
        $connection['username'],
        $connection['password']
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données réussie\n";
    
    // Vérifier si des entreprises existent
    $stmt = $pdo->query("SELECT COUNT(*) FROM companies");
    $companyCount = $stmt->fetchColumn();
    
    if ($companyCount == 0) {
        echo "Insertion des entreprises de test...\n";
        
        $companies = [
            ['name' => 'TPT INTERNATIONAL', 'email' => 'contact@tptinternational.com', 'phone' => '+225 21 23 45 67', 'address' => 'Abidjan, Cocody', 'is_active' => 1],
            ['name' => 'TPT SOLUTIONS', 'email' => 'info@tpth-solutions.com', 'phone' => '+225 22 34 56 78', 'address' => 'Abidjan, Plateau', 'is_active' => 1],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO companies (name, email, phone, address, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        
        foreach ($companies as $company) {
            $stmt->execute([$company['name'], $company['email'], $company['phone'], $company['address'], $company['is_active']]);
        }
        
        echo "Entreprises insérées avec succès\n";
    } else {
        echo "Entreprises déjà présentes: $companyCount\n";
    }
    
    // Vérifier les centres de coûts
    $stmt = $pdo->query("SELECT COUNT(*) FROM cost_centers");
    $costCenterCount = $stmt->fetchColumn();
    
    if ($costCenterCount == 0) {
        echo "Insertion des centres de coûts...\n";
        
        $stmt = $pdo->prepare("INSERT INTO cost_centers (company_id, code, name, description, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        
        $stmt->execute([1, 'ADMIN', 'Administration', 'Centre de coûts pour l\'administration', 1]);
        $stmt->execute([1, 'PROD', 'Production', 'Centre de coûts pour la production', 1]);
        $stmt->execute([1, 'COMM', 'Commercial', 'Centre de coûts pour le commercial', 1]);
        $stmt->execute([2, 'ADMIN', 'Administration', 'Centre de coûts pour l\'administration', 1]);
        $stmt->execute([2, 'IT', 'Informatique', 'Centre de coûts pour l\'informatique', 1]);
        
        echo "Centres de coûts insérés avec succès\n";
    } else {
        echo "Centres de coûts déjà présents: $costCenterCount\n";
    }
    
    // Vérifier les projets
    $stmt = $pdo->query("SELECT COUNT(*) FROM projects");
    $projectCount = $stmt->fetchColumn();
    
    if ($projectCount == 0) {
        echo "Insertion des projets...\n";
        
        $stmt = $pdo->prepare("INSERT INTO projects (company_id, code, name, description, budget_amount, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        $stmt->execute([1, 'ERP001', 'Déploiement ERP', 'Projet de déploiement du système ERP', 50000000, 'actif']);
        $stmt->execute([1, 'WEB001', 'Site Web Corporate', 'Développement du site web corporate', 15000000, 'en_attente']);
        $stmt->execute([2, 'APP001', 'Application Mobile', 'Développement application mobile', 30000000, 'actif']);
        
        echo "Projets insérés avec succès\n";
    } else {
        echo "Projets déjà présents: $projectCount\n";
    }
    
    // Vérifier les clients
    $stmt = $pdo->query("SELECT COUNT(*) FROM clients");
    $clientCount = $stmt->fetchColumn();
    
    if ($clientCount == 0) {
        echo "Insertion des clients...\n";
        
        $stmt = $pdo->prepare("INSERT INTO clients (company_id, code, name, email, phone, address, type_client, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        $stmt->execute([1, 'CLI001', 'Société Générale CI', 'contact@sgci.ci', '+225 21 20 30 40', 'Abidjan, Plateau', 'entreprise', 1]);
        $stmt->execute([1, 'CLI002', 'Banque Atlantique', 'info@banque-atlantique.ci', '+225 22 40 50 60', 'Abidjan, Cocody', 'entreprise', 1]);
        $stmt->execute([2, 'CLI003', 'Ministère des Finances', 'contact@finances.gouv.ci', '+225 21 70 80 90', 'Abidjan', 'gouvernement', 1]);
        
        echo "Clients insérés avec succès\n";
    } else {
        echo "Clients déjà présents: $clientCount\n";
    }
    
    // Vérifier les fournisseurs
    $stmt = $pdo->query("SELECT COUNT(*) FROM fournisseurs");
    $fournisseurCount = $stmt->fetchColumn();
    
    if ($fournisseurCount == 0) {
        echo "Insertion des fournisseurs...\n";
        
        $stmt = $pdo->prepare("INSERT INTO fournisseurs (company_id, code, name, email, phone, address, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        $stmt->execute([1, 'FOUR001', 'Dell Technologies', 'contact@dell.com', '+1 800 622 5575', 'USA', 1]);
        $stmt->execute([1, 'FOUR002', 'Microsoft', 'contact@microsoft.com', '+1 800 642 7676', 'USA', 1]);
        $stmt->execute([2, 'FOUR003', 'Oracle', 'contact@oracle.com', '+1 800 222 5577', 'USA', 1]);
        
        echo "Fournisseurs insérés avec succès\n";
    } else {
        echo "Fournisseurs déjà présents: $fournisseurCount\n";
    }
    
    echo "\n✅ Données de test insérées avec succès !\n";
    echo "Vous pouvez maintenant accéder aux modules du système.\n";
    
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}