<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignMissingRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:assign-missing {--force : Force l\'attribution sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attribuer les rôles manquants aux utilisateurs selon leur fonction';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Cette opération va attribuer les rôles manquants aux utilisateurs. Continuer ?')) {
                return 1;
            }
        }

        $this->info('Attribution des rôles manquants en cours...');

        try {
            // Récupérer tous les utilisateurs
            $users = User::with('roles')->get();

            foreach ($users as $user) {
                $this->processUser($user);
            }

            $this->info('✅ Attribution des rôles terminée avec succès!');
            
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'attribution des rôles: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function processUser($user)
    {
        $currentRoles = $user->roles->pluck('slug')->toArray();
        $name = strtolower($user->prenom . ' ' . $user->nom);
        $full_name = strtolower($user->nom . ' ' . $user->prenom . ' ' . $user->prenom);

        // Déterminer le rôle basé sur le nom ou d'autres critères
        $roleToAssign = $this->determineRole($full_name, $currentRoles);

        if ($roleToAssign && !in_array($roleToAssign, $currentRoles)) {
            $role = Role::where('slug', $roleToAssign)->first();
            
            if ($role) {
                $user->assignRole($role, 1); // Utiliser ID 1 comme utilisateur par défaut
                $this->info("  ✓ {$user->prenom} {$user->nom} (ID: {$user->id}) -> {$role->nom}");
            } else {
                $this->warn("  ⚠ Rôle '{$roleToAssign}' non trouvé pour {$user->prenom} {$user->nom}");
            }
        } elseif ($roleToAssign) {
            $this->line("  - {$user->prenom} {$user->nom} (ID: {$user->id}) -> Déjà attribué ({$roleToAssign})");
        } else {
            $this->line("  - {$user->prenom} {$user->nom} (ID: {$user->id}) -> Aucun rôle déterminé");
        }
    }

    private function determineRole($name, $currentRoles)
    {
        // Déterminer le rôle basé sur le nom - ignorer si l'utilisateur a déjà un rôle similaire
        $name = strtolower($name);
        
        if (strpos($name, 'administrateur') !== false || strpos($name, 'systeme') !== false || strpos($name, 'système') !== false) {
            if (!in_array('administrateur', $currentRoles) && !in_array('admin', $currentRoles)) {
                return 'administrateur';
            }
        } elseif (strpos($name, 'gestionnaire') !== false || strpos($name, 'manager') !== false || strpos($name, 'directeur') !== false) {
            if (!in_array('manager', $currentRoles)) {
                return 'manager';
            }
        } elseif (strpos($name, 'superviseur') !== false || strpos($name, 'superviseur') !== false) {
            if (!in_array('supervisor', $currentRoles)) {
                return 'supervisor';
            }
        } elseif (strpos($name, 'achats') !== false || strpos($name, 'achat') !== false || strpos($name, 'purchas') !== false) {
            if (!in_array('purchases', $currentRoles)) {
                return 'purchases';
            }
        } elseif (strpos($name, 'comptabilité') !== false || strpos($name, 'compta') !== false || strpos($name, 'accounting') !== false) {
            if (!in_array('accounting', $currentRoles)) {
                return 'accounting';
            }
        } elseif (strpos($name, 'rh') !== false || strpos($name, 'ressources humaines') !== false || strpos($name, 'human ressource') !== false) {
            if (!in_array('hr', $currentRoles)) {
                return 'hr';
            }
        } elseif (strpos($name, 'caisse') !== false || strpos($name, 'cash') !== false) {
            if (!in_array('cash', $currentRoles)) {
                return 'cash';
            }
        } elseif (strpos($name, 'client') !== false || strpos($name, 'commercial') !== false) {
            if (!in_array('commercial', $currentRoles)) {
                return 'commercial';
            }
        } elseif (strpos($name, 'fournisseur') !== false || strpos($name, 'supplier') !== false) {
            if (!in_array('supplier', $currentRoles)) {
                return 'supplier';
            }
        } elseif (strpos($name, 'opérationnel') !== false || strpos($name, 'operationnel') !== false || strpos($name, 'operational') !== false) {
            if (!in_array('operational', $currentRoles)) {
                return 'operational';
            }
        } elseif (strpos($name, 'consultant') !== false || strpos($name, 'externe') !== false) {
            if (!in_array('consultant', $currentRoles)) {
                return 'consultant';
            }
        } elseif (strpos($name, 'admin') !== false) {
            if (!in_array('admin', $currentRoles) && !in_array('administrateur', $currentRoles)) {
                return 'admin';
            }
        }

        // Retourner null si aucun rôle supplémentaire n'est nécessaire
        return null;
    }
}