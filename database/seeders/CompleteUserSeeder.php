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
                'nom' => 'Administrateur',
                'prenom' => 'Système',
                'email' => 'admin@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'manager',
                'role_name' => 'Gestionnaire',
                'nom' => 'Gestionnaire',
                'prenom' => 'Principal',
                'email' => 'manager@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'supervisor',
                'role_name' => 'Superviseur',
                'nom' => 'Superviseur',
                'prenom' => 'Equipe',
                'email' => 'supervisor@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'agent',
                'role_name' => 'Agent Opérationnel',
                'nom' => 'Agent',
                'prenom' => 'Opérationnel',
                'email' => 'agent@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'viewer',
                'role_name' => 'Consultant',
                'nom' => 'Consultant',
                'prenom' => 'Externe',
                'email' => 'viewer@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'hr',
                'role_name' => 'Ressources Humaines',
                'nom' => 'Ressources',
                'prenom' => 'Humaines',
                'email' => 'hr@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'accounting',
                'role_name' => 'Comptabilité',
                'nom' => 'Service',
                'prenom' => 'Comptabilité',
                'email' => 'accounting@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'purchases',
                'role_name' => 'Achats',
                'nom' => 'Service',
                'prenom' => 'Achats',
                'email' => 'purchases@tpt-h.com',
                'password' => 'password',
            ],
            [
                'role_slug' => 'supplier',
                'role_name' => 'Fournisseur',
                'nom' => 'Fournisseur',
                'prenom' => 'Externe',
                'email' => 'fournisseur@tpt-h.com',
                'password' => 'password',
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
                    'telephone' => '0000000000', // Default phone number
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
