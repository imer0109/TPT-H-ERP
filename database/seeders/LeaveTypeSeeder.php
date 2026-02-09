<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Congé Annuel',
                'description' => 'Congé payé annuel standard',
                'default_days' => 21,
                'is_paid' => true,
                'requires_approval' => true,
                'affects_salary' => false,
            ],
            [
                'name' => 'Congé Maladie',
                'description' => 'Arrêt maladie avec justificatif médical',
                'default_days' => 0,
                'is_paid' => true,
                'requires_approval' => true,
                'affects_salary' => false,
            ],
            [
                'name' => 'Congé Maternité',
                'description' => 'Congé de maternité',
                'default_days' => 98,
                'is_paid' => true,
                'requires_approval' => true,
                'affects_salary' => false,
            ],
            [
                'name' => 'Congé Paternité',
                'description' => 'Congé de paternité',
                'default_days' => 3,
                'is_paid' => true,
                'requires_approval' => true,
                'affects_salary' => false,
            ],
            [
                'name' => 'Congé Sans Solde',
                'description' => 'Congé sans rémunération',
                'default_days' => 0,
                'is_paid' => false,
                'requires_approval' => true,
                'affects_salary' => true,
            ],
            [
                'name' => 'Congé Formation',
                'description' => 'Congé pour formation professionnelle',
                'default_days' => 0,
                'is_paid' => true,
                'requires_approval' => true,
                'affects_salary' => false,
            ],
            [
                'name' => 'Congé Exceptionnel',
                'description' => 'Congé pour événements familiaux (mariage, décès, etc.)',
                'default_days' => 3,
                'is_paid' => true,
                'requires_approval' => true,
                'affects_salary' => false,
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}