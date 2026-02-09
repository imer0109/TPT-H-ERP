<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rôles de base à créer des utilisateurs pour
        $baseRoles = [
            ['nom' => 'Administrateur Système', 'slug' => 'administrateur', 'email' => 'admin@tpt-h.com'],
            ['nom' => 'Gestionnaire', 'slug' => 'manager', 'email' => 'manager@tpt-h.com'],
            ['nom' => 'Superviseur', 'slug' => 'supervisor', 'email' => 'supervisor@tpt-h.com'],
            ['nom' => 'Agent Opérationnel', 'slug' => 'agent', 'email' => 'agent@tpt-h.com'],
            ['nom' => 'Consultant', 'slug' => 'viewer', 'email' => 'viewer@tpt-h.com'],
        ];
        
        // Rôles supplémentaires à créer si nécessaire
        $additionalRoles = [
            ['nom' => 'Ressources Humaines', 'slug' => 'hr', 'email' => 'hr@tpt-h.com', 'description' => 'Rôle pour la gestion des ressources humaines'],
            ['nom' => 'Comptabilité', 'slug' => 'accounting', 'email' => 'accounting@tpt-h.com', 'description' => 'Rôle pour la gestion comptable'],
            ['nom' => 'Achats', 'slug' => 'purchases', 'email' => 'purchases@tpt-h.com', 'description' => 'Rôle pour la gestion des achats'],
            ['nom' => 'Fournisseur', 'slug' => 'supplier', 'email' => 'supplier@tpt-h.com', 'description' => 'Rôle pour l\'accès espace fournisseur'],
        ];
        
        // Créer les rôles supplémentaires s'ils n'existent pas
        foreach ($additionalRoles as $roleData) {
            \App\Models\Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                [
                    'nom' => $roleData['nom'],
                    'description' => $roleData['description'],
                    'color' => $this->getColorForRole($roleData['slug']),
                    'is_system' => true,
                ]
            );
        }
        
        // Fusionner les rôles de base et supplémentaires
        $roles = array_merge($baseRoles, $additionalRoles);
        
        foreach ($roles as $roleData) {
            $role = \App\Models\Role::where('slug', $roleData['slug'])->first();
            
            if ($role) {
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $roleData['email']],
                    [
                        'nom' => explode(' ', $roleData['nom'])[0],
                        'prenom' => explode(' ', $roleData['nom'])[1] ?? 'User',
                        'email' => $roleData['email'],
                        'password' => bcrypt('password'),
                        'telephone' => '000000000',
                        'statut' => 'actif',
                        'email_verified_at' => now(),
                    ]
                );
                
                // Force update password to ensure it matches the documentation
                $user->update(['password' => bcrypt('password')]);
                
                // Attacher l'utilisateur au rôle s'il n'est pas déjà attaché
                if (!$user->roles()->where('role_id', $role->id)->exists()) {
                    $user->roles()->attach($role->id);
                }
                
                $this->command->info("Utilisateur créé pour le rôle {$roleData['nom']}: {$roleData['email']} - Mot de passe: password");
            } else {
                $this->command->info("Rôle {$roleData['nom']} non trouvé");
            }
        }
    }
    
    private function getColorForRole($slug)
    {
        $colors = [
            'hr' => '#f59e0b',
            'accounting' => '#10b981',
            'purchases' => '#8b5cf6',
            'supplier' => '#dc2626',
        ];
        
        return $colors[$slug] ?? '#94a3b8';
    }
}