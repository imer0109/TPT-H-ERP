<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Employee;

class LinkAdminToEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-admin-to-employee {--user-id=} {--employee-id=} {--interactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lier un utilisateur à un employé';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('interactive')) {
            // Mode interactif
            $this->interactiveMode();
        } else {
            // Mode avec paramètres
            $userId = $this->option('user-id');
            $employeeId = $this->option('employee-id');

            if (!$userId || !$employeeId) {
                $this->error('Les options --user-id et --employee-id sont requises.');
                return 1;
            }

            $this->linkUserToEmployee($userId, $employeeId);
        }

        return 0;
    }

    /**
     * Mode interactif pour lier un utilisateur à un employé
     */
    private function interactiveMode()
    {
        // Afficher les administrateurs
        $admins = User::whereHas('roles', function($q) {
            $q->where('slug', 'administrateur');
        })->get(['id', 'nom', 'prenom']);

        if ($admins->isEmpty()) {
            $this->error("Aucun administrateur trouvé.");
            return 1;
        }

        $this->info("Administrateurs trouvés :");
        foreach ($admins as $admin) {
            $this->line("- ID: {$admin->id}, Nom: {$admin->prenom} {$admin->nom}");
        }

        // Afficher les employés
        $employees = Employee::all(['id', 'first_name', 'last_name']);

        if ($employees->isEmpty()) {
            $this->error("Aucun employé trouvé.");
            return 1;
        }

        $this->info("\nEmployés trouvés :");
        foreach ($employees as $employee) {
            $this->line("- ID: {$employee->id}, Nom: {$employee->first_name} {$employee->last_name}");
        }

        // Demander les IDs
        $userId = $this->ask('Entrez l\'ID de l\'administrateur à lier');
        $employeeId = $this->ask('Entrez l\'ID de l\'employé à lier');

        $this->linkUserToEmployee($userId, $employeeId);
    }

    /**
     * Lier un utilisateur à un employé
     */
    private function linkUserToEmployee($userId, $employeeId)
    {
        $user = User::find($userId);
        $employee = Employee::find($employeeId);

        if (!$user) {
            $this->error("Utilisateur avec l'ID {$userId} non trouvé.");
            return 1;
        }

        if (!$employee) {
            $this->error("Employé avec l'ID {$employeeId} non trouvé.");
            return 1;
        }

        $employee->user_id = $userId;
        $employee->save();

        $this->info("L'utilisateur {$user->prenom} {$user->nom} a été lié à l'employé {$employee->first_name} {$employee->last_name}.");
    }
}