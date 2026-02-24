<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Company;
use App\Models\Position;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $companies = Company::all();
        $positions = Position::all();
        
        $employeesData = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@tpth.fr',
                'phone' => '+33 1 23 45 67 80',
                'birth_date' => '1985-03-15',
                'date_embauche' => '2020-01-15',
                'salaire_base' => 5000.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Martin',
                'email' => 'marie.martin@tpth.fr',
                'phone' => '+33 1 23 45 67 81',
                'birth_date' => '1990-07-22',
                'date_embauche' => '2021-03-01',
                'salaire_base' => 4500.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Pierre',
                'last_name' => 'Bernard',
                'email' => 'pierre.bernard@tpth.fr',
                'phone' => '+33 1 23 45 67 82',
                'birth_date' => '1988-11-08',
                'date_embauche' => '2019-06-10',
                'salaire_base' => 4800.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Petit',
                'email' => 'sophie.petit@tpth.fr',
                'phone' => '+33 1 23 45 67 83',
                'birth_date' => '1992-01-30',
                'date_embauche' => '2022-02-15',
                'salaire_base' => 4200.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Thomas',
                'last_name' => 'Robert',
                'email' => 'thomas.robert@tpth.fr',
                'phone' => '+33 1 23 45 67 84',
                'birth_date' => '1987-09-12',
                'date_embauche' => '2020-11-01',
                'salaire_base' => 4600.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Céline',
                'last_name' => 'Richard',
                'email' => 'celine.richard@tpth.fr',
                'phone' => '+33 1 23 45 67 85',
                'birth_date' => '1991-05-18',
                'date_embauche' => '2021-08-20',
                'salaire_base' => 4300.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Alexandre',
                'last_name' => 'Durand',
                'email' => 'alexandre.durand@tpth.fr',
                'phone' => '+33 1 23 45 67 86',
                'birth_date' => '1989-12-03',
                'date_embauche' => '2019-09-05',
                'salaire_base' => 4900.00,
                'status' => 'active'
            ],
            [
                'first_name' => 'Émilie',
                'last_name' => 'Leroy',
                'email' => 'emilie.leroy@tpth.fr',
                'phone' => '+33 1 23 45 67 87',
                'birth_date' => '1993-04-25',
                'date_embauche' => '2022-01-10',
                'salaire_base' => 4100.00,
                'status' => 'active'
            ]
        ];

        foreach ($employeesData as $index => $empData) {
            $company = $companies->random();
            $position = $positions->random();
            $agency = $company->agencies()->first() ?? $company->agencies()->create([
                'nom' => 'Agence principale ' . $company->name,
                'adresse' => 'Adresse de l\'agence',
                'code_unique' => 'AG' . time() . $index,
                'responsable_id' => 1,
                'zone_geographique' => 'France'
            ]);
            $supervisor = $index > 0 ? Employee::inRandomOrder()->first() : null;
            
            Employee::firstOrCreate(
                ['email' => $empData['email']],
                array_merge($empData, [
                    'current_company_id' => $company->id,
                    'current_agency_id' => $agency->id,
                    'current_position_id' => $position->id,
                    'supervisor_id' => $supervisor?->id,
                    'gender' => $index % 2 == 0 ? 'M' : 'F',
                    'nationality' => 'Française',
                    'birth_place' => 'Paris',
                    'id_card_number' => 'ID' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    'address' => '1 Rue de l\'Entreprise, 75001 Paris',
                    'matricule' => 'EMP' . str_pad($index + 1, 4, '0', STR_PAD_LEFT)
                ])
            );
        }
    }
}