<?php

use App\Models\User;
use App\Models\Company;
use App\Models\ValidationWorkflow;
use App\Models\ValidationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can create a validation workflow', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();
    
    $workflowData = [
        'name' => 'Test Workflow',
        'description' => 'Workflow de test pour décaissements',
        'module' => 'accounting',
        'entity_type' => 'App\Models\AccountingEntry',
        'company_id' => $company->id,
        'conditions' => [
            [
                'field' => 'amount',
                'operator' => 'greater_than',
                'value' => 100000
            ]
        ],
        'steps' => [
            [
                'name' => 'Validation Chef',
                'description' => 'Validation par le chef de service',
                'role' => 'chef_service',
                'timeout_hours' => 24
            ],
            [
                'name' => 'Validation DG',
                'description' => 'Validation par le Directeur Général',
                'role' => 'directeur_general',
                'timeout_hours' => 48
            ]
        ],
        'is_active' => true,
        'created_by' => $user->id
    ];
    
    $workflow = ValidationWorkflow::create($workflowData);
    
    expect($workflow)->toBeInstanceOf(ValidationWorkflow::class);
    expect($workflow->name)->toBe('Test Workflow');
    expect($workflow->module)->toBe('accounting');
    expect(count($workflow->steps))->toBe(2);
});

test('it can create a validation request', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();
    
    $workflow = ValidationWorkflow::create([
        'name' => 'Test Workflow',
        'description' => 'Workflow de test pour décaissements',
        'module' => 'accounting',
        'entity_type' => 'App\Models\AccountingEntry',
        'company_id' => $company->id,
        'conditions' => [],
        'steps' => [
            [
                'name' => 'Validation Chef',
                'description' => 'Validation par le chef de service',
                'role' => 'chef_service',
                'timeout_hours' => 24
            ]
        ],
        'is_active' => true,
        'created_by' => $user->id
    ]);
    
    $requestData = [
        'workflow_id' => $workflow->id,
        'entity_type' => 'App\Models\AccountingEntry',
        'entity_id' => 1,
        'company_id' => $company->id,
        'requested_by' => $user->id,
        'current_step' => 0,
        'status' => 'pending',
        'reason' => 'Test de validation',
        'data_snapshot' => ['amount' => 150000]
    ];
    
    $request = ValidationRequest::create($requestData);
    
    expect($request)->toBeInstanceOf(ValidationRequest::class);
    expect($request->status)->toBe('pending');
    expect($request->reason)->toBe('Test de validation');
});

test('it can approve a validation request', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();
    
    $workflow = ValidationWorkflow::create([
        'name' => 'Test Workflow',
        'description' => 'Workflow de test pour décaissements',
        'module' => 'accounting',
        'entity_type' => 'App\Models\AccountingEntry',
        'company_id' => $company->id,
        'conditions' => [],
        'steps' => [
            [
                'name' => 'Validation Chef',
                'description' => 'Validation par le chef de service',
                'role' => 'chef_service',
                'timeout_hours' => 24
            ]
        ],
        'is_active' => true,
        'created_by' => $user->id
    ]);
    
    $request = ValidationRequest::create([
        'workflow_id' => $workflow->id,
        'entity_type' => 'App\Models\AccountingEntry',
        'entity_id' => 1,
        'company_id' => $company->id,
        'requested_by' => $user->id,
        'current_step' => 0,
        'status' => 'pending',
        'reason' => 'Test de validation',
        'data_snapshot' => ['amount' => 150000]
    ]);
    
    // Approve the request
    $request->approve($user->id, 'Approved for testing');
    
    expect($request->status)->toBe('approved');
    expect($request->completed_at)->not->toBeNull();
});