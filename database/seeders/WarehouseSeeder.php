<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\User;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $users = User::limit(5)->get();
        
        $warehouses = [
            [
                'nom' => 'Entrepôt Principal Paris',
                'code' => 'PAR-001',
                'description' => 'Entrepôt principal de Paris pour le stockage général',
                'adresse' => '123 Avenue des Entrepôts, 75015 Paris',
                'type' => 'principal',
                'actif' => true
            ],
            [
                'nom' => 'Entrepôt Lyon',
                'code' => 'LYO-001',
                'description' => 'Entrepôt de Lyon pour la distribution régionale',
                'adresse' => '45 Rue de la Logistique, 69009 Lyon',
                'type' => 'secondaire',
                'actif' => true
            ],
            [
                'nom' => 'Entrepôt Marseille',
                'code' => 'MAR-001',
                'description' => 'Entrepôt de Marseille pour les produits du sud',
                'adresse' => '78 Boulevard du Commerce, 13001 Marseille',
                'type' => 'secondaire',
                'actif' => true
            ],
            [
                'nom' => 'Entrepôt Toulouse',
                'code' => 'TOU-001',
                'description' => 'Entrepôt de Toulouse pour la distribution du sud-ouest',
                'adresse' => '22 Avenue de l\'Industrie, 31000 Toulouse',
                'type' => 'secondaire',
                'actif' => true
            ],
            [
                'nom' => 'Entrepôt Nantes',
                'code' => 'NAN-001',
                'description' => 'Entrepôt de Nantes pour les produits de l\'ouest',
                'adresse' => '56 Rue des Marchandises, 44000 Nantes',
                'type' => 'secondaire',
                'actif' => true
            ]
        ];

        foreach ($warehouses as $warehouseData) {
            $company = $companies->random();
            $user = $users->random();
            
            Warehouse::firstOrCreate(
                ['code' => $warehouseData['code']],
                array_merge($warehouseData, [
                    'entity_type' => 'company',
                    'entity_id' => $company->id,
                    'created_by' => $user->id
                ])
            );
        }
    }
}
