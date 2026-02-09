<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RolePermissionService;
use App\Models\Company;

class InitializeSecurity extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:init {--company-id= : ID of the company to initialize security for}';

    /**
     * The console command description.
     */
    protected $description = 'Initialize security system with default roles and permissions';

    protected $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        parent::__construct();
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initializing security system...');

        $companyId = $this->option('company-id');

        if ($companyId) {
            $company = Company::find($companyId);
            if (!$company) {
                $this->error("Company with ID {$companyId} not found.");
                return 1;
            }
            $this->initializeForCompany($company);
        } else {
            // Initialize for all companies
            $companies = Company::all();
            foreach ($companies as $company) {
                $this->initializeForCompany($company);
            }
        }

        $this->info('Security system initialization completed!');
        return 0;
    }

    protected function initializeForCompany(Company $company)
    {
        $this->info("Initializing security for company: {$company->name}");

        // Create default permissions (only once, they're global)
        if (!$this->option('company-id') || $company->id === Company::first()->id) {
            $this->info('Creating default permissions...');
            $this->rolePermissionService->createDefaultPermissions();
        }

        // Create default roles for this company
        $this->info("Creating default roles for {$company->name}...");
        $this->rolePermissionService->createDefaultRoles($company->id);

        // Assign default permissions to roles
        $this->info("Assigning permissions to roles for {$company->name}...");
        $this->rolePermissionService->assignDefaultPermissions($company->id);

        $this->info("✓ Security initialized for {$company->name}");
    }
}