<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CashRegister;
use App\Models\Company;
use App\Models\Agency;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $agencies = Agency::all();
        
        $cashRegisters = [
            [
                'nom' => 'Caisse Principale Paris',
                'type' => 'principale',
                'solde_actuel' => 15000.00,
                'est_ouverte' => true
            ],
            [
                'nom' => 'Caisse Lyon',
                'type' => 'secondaire',
                'solde_actuel' => 8500.00,
                'est_ouverte' => true
            ],
            [
                'nom' => 'Caisse Marseille',
                'type' => 'secondaire',
                'solde_actuel' => 12000.00,
                'est_ouverte' => false
            ],
            [
                'nom' => 'Caisse Toulouse',
                'type' => 'secondaire',
                'solde_actuel' => 6500.00,
                'est_ouverte' => true
            ],
            [
                'nom' => 'Caisse Nantes',
                'type' => 'secondaire',
                'solde_actuel' => 9200.00,
                'est_ouverte' => true
            ]
        ];

        foreach ($cashRegisters as $index => $cashData) {
            if ($index < 2) {
                // Pour les 2 premières caisses, les lier aux entreprises
                $company = $companies->random();
                $entityType = 'company';
                $entityId = $company->id;
            } else {
                // Pour les autres, les lier aux agences
                $agency = $agencies->random();
                $entityType = 'agency';
                $entityId = $agency->id;
            }
            
            CashRegister::firstOrCreate(
                ['nom' => $cashData['nom']],
                array_merge($cashData, [
                    'entity_type' => $entityType,
                    'entity_id' => $entityId
                ])
            );
        }
    }
}
