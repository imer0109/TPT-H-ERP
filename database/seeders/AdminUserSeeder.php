<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur super administrateur s'il n'existe pas
        $adminUser = \App\Models\User::firstOrCreate(
            ['email' => 'admin@tpt-h.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@tpt-h.com',
                'password' => bcrypt('password'),
                'telephone' => '000000000',
                'statut' => 'actif',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Utilisateur administrateur créé avec succès !');
        $this->command->info('Email: admin@tpt-h.com');
        $this->command->info('Mot de passe: password');
    }
}
