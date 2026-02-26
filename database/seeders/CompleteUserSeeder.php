<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CompleteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist by calling RolesAndPermissionsSeeder
        // We can skip this if we are sure it's called in DatabaseSeeder, but it's safer to have roles.
        // However, usually we don't call seeders from seeders unless necessary to avoid duplication if called from DatabaseSeeder.
        // I will assume RolesAndPermissionsSeeder is called before this in DatabaseSeeder.

        $users = [
            [
                'role_slug' => 'administrateur',
                'role_name' => 'Administrateur Système',
                'nom' => 'Dubois',
                'prenom' => 'Alexandre',
                'email' => 'admin@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 01'
            ],
            [
                'role_slug' => 'manager',
                'role_name' => 'Gestionnaire',
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'email' => 'manager@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 02'
            ],
            [
                'role_slug' => 'supervisor',
                'role_name' => 'Superviseur',
                'nom' => 'Bernard',
                'prenom' => 'Thomas',
                'email' => 'supervisor@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 03'
            ],
            [
                'role_slug' => 'agent',
                'role_name' => 'Agent Opérationnel',
                'nom' => 'Petit',
                'prenom' => 'Marie',
                'email' => 'agent@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 04'
            ],
            [
                'role_slug' => 'viewer',
                'role_name' => 'Consultant',
                'nom' => 'Robert',
                'prenom' => 'Pierre',
                'email' => 'viewer@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 05'
            ],
            [
                'role_slug' => 'hr',
                'role_name' => 'Ressources Humaines',
                'nom' => 'Richard',
                'prenom' => 'Céline',
                'email' => 'hr@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 06'
            ],
            [
                'role_slug' => 'accounting',
                'role_name' => 'Comptabilité',
                'nom' => 'Durand',
                'prenom' => 'Jean',
                'email' => 'accounting@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 07'
            ],
            [
                'role_slug' => 'purchases',
                'role_name' => 'Achats',
                'nom' => 'Leroy',
                'prenom' => 'Émilie',
                'email' => 'purchases@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 08'
            ],
            [
                'role_slug' => 'supplier',
                'role_name' => 'Fournisseur',
                'nom' => 'Moreau',
                'prenom' => 'Antoine',
                'email' => 'fournisseur@tpt-h.com',
                'password' => 'password',
                'telephone' => '+33 1 00 00 00 09'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'nom' => $userData['nom'],
                    'prenom' => $userData['prenom'],
                    'password' => Hash::make($userData['password']),
                    'statut' => 'actif',
                    'telephone' => $userData['telephone'] ?? '0000000000',
                ]
            );

            // Find role
            $role = Role::where('slug', $userData['role_slug'])->first();
            
            if ($role) {
                // Assign role if not already assigned
                if (!$user->roles()->where('roles.id', $role->id)->exists()) {
                    $user->roles()->attach($role->id);
                    $this->command->info("Assigned role {$role->nom} to user {$user->email}");
                }
            } else {
                $this->command->error("Role {$userData['role_slug']} not found for user {$user->email}");
            }
        }
    }
}
