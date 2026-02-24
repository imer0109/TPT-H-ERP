<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Société principale TPT-H
        Company::firstOrCreate(
            ['raison_sociale' => 'TPT-H INTERNATIONAL'],
            [
                'type' => 'holding',
                'niu' => 'NIU-TPTH-001',
                'rccm' => 'RCCM-TPTH-001',
                'regime_fiscal' => 'Réel normal',
                'secteur_activite' => 'Services informatiques',
                'devise' => 'EUR',
                'pays' => 'France',
                'ville' => 'Paris',
                'siege_social' => '15 Rue de la Paix, 75002 Paris',
                'email' => 'contact@tpth.fr',
                'telephone' => '+33 1 23 45 67 89',
                'whatsapp' => '+33 6 12 34 56 78',
                'site_web' => 'https://www.tpth.fr',
                'active' => true
            ]
        );

        // Filiale TPT-H Services
        $holding = Company::where('raison_sociale', 'TPT-H INTERNATIONAL')->first();
        
        Company::firstOrCreate(
            ['raison_sociale' => 'TPT-H SERVICES'],
            [
                'type' => 'filiale',
                'parent_id' => $holding->id,
                'niu' => 'NIU-TPTHS-001',
                'rccm' => 'RCCM-TPTHS-001',
                'regime_fiscal' => 'Réel normal',
                'secteur_activite' => 'Développement logiciel',
                'devise' => 'EUR',
                'pays' => 'France',
                'ville' => 'Lyon',
                'siege_social' => '25 Avenue des Bergères, 69009 Lyon',
                'email' => 'services@tpth.fr',
                'telephone' => '+33 4 72 12 34 56',
                'active' => true
            ]
        );

        // Filiale TPT-H Consulting
        Company::firstOrCreate(
            ['raison_sociale' => 'TPT-H CONSULTING'],
            [
                'type' => 'filiale',
                'parent_id' => $holding->id,
                'niu' => 'NIU-TPTH-C-001',
                'rccm' => 'RCCM-TPTH-C-001',
                'regime_fiscal' => 'Réel normal',
                'secteur_activite' => 'Conseil en informatique',
                'devise' => 'EUR',
                'pays' => 'France',
                'ville' => 'Marseille',
                'siege_social' => '12 Boulevard Longchamp, 13001 Marseille',
                'email' => 'consulting@tpth.fr',
                'telephone' => '+33 4 91 23 45 67',
                'active' => true
            ]
        );
    }
}
