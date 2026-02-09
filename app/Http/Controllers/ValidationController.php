<?php

namespace App\Http\Controllers;

use App\Models\ValidationWorkflow;
use App\Models\ValidationRequest;
use App\Models\ValidationStep;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    /**
     * Display a listing of validation workflows
     */
    public function index()
    {
        $workflows = ValidationWorkflow::with('company')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('validations.workflows.index', compact('workflows'));
    }

    /**
     * Show the form for creating a new validation workflow
     */
    public function create()
    {
        $companies = Company::all();
        return view('validations.workflows.create', compact('companies'));
    }

    /**
     * Store a newly created validation workflow
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string|max:100',
            'entity_type' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'is_active' => 'boolean',
            'steps' => 'required|array',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.role' => 'required|string|max:100',
            'steps.*.timeout_hours' => 'nullable|integer|min:1',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', false);

        ValidationWorkflow::create($validated);

        return redirect()->route('validations.workflows.index')
            ->with('success', 'Workflow de validation créé avec succès.');
    }

    /**
     * Display the specified validation workflow
     */
    public function show(ValidationWorkflow $workflow)
    {
        $workflow->load('validationRequests');
        return view('validations.workflows.show', compact('workflow'));
    }

    /**
     * Show the form for editing the specified validation workflow
     */
    public function edit(ValidationWorkflow $workflow)
    {
        $companies = Company::all();
        return view('validations.workflows.edit', compact('workflow', 'companies'));
    }

    /**
     * Update the specified validation workflow
     */
    public function update(Request $request, ValidationWorkflow $workflow)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string|max:100',
            'entity_type' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'is_active' => 'boolean',
            'steps' => 'required|array',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.role' => 'required|string|max:100',
            'steps.*.timeout_hours' => 'nullable|integer|min:1',
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', false);

        $workflow->update($validated);

        return redirect()->route('validations.workflows.index')
            ->with('success', 'Workflow de validation mis à jour avec succès.');
    }

    /**
     * Remove the specified validation workflow
     */
    public function destroy(ValidationWorkflow $workflow)
    {
        $workflow->delete();

        return redirect()->route('validations.workflows.index')
            ->with('success', 'Workflow de validation supprimé avec succès.');
    }

    /**
     * Display a listing of validation requests
     */
    public function requests()
    {
        $requests = ValidationRequest::with(['workflow', 'company', 'requester'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('validations.requests.index', compact('requests'));
    }

    /**
     * Display the specified validation request
     */
    public function showRequest(ValidationRequest $request)
    {
        $request->load(['workflow', 'company', 'requester', 'validationSteps.validator']);
        return view('validations.requests.show', compact('request'));
    }

    /**
     * Approve a validation request
     */
    public function approveRequest(Request $request, ValidationRequest $validationRequest)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $validationRequest->approve(Auth::id(), $validated['notes'] ?? null);

        return redirect()->back()
            ->with('success', 'Demande approuvée avec succès.');
    }

    /**
     * Reject a validation request
     */
    public function rejectRequest(Request $request, ValidationRequest $validationRequest)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $validationRequest->reject(Auth::id(), $validated['reason']);

        return redirect()->back()
            ->with('success', 'Demande rejetée avec succès.');
    }

    /**
     * Delegate a validation request to another user
     */
    public function delegateRequest(Request $request, ValidationRequest $validationRequest)
    {
        $validated = $request->validate([
            'delegate_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create a validation step for delegation
        ValidationStep::create([
            'request_id' => $validationRequest->id,
            'step_number' => $validationRequest->current_step,
            'validator_id' => Auth::id(),
            'action' => 'delegated',
            'notes' => $validated['notes'] ?? null,
            'validated_at' => now()
        ]);

        // Create a new step for the delegate
        ValidationStep::create([
            'request_id' => $validationRequest->id,
            'step_number' => $validationRequest->current_step,
            'validator_id' => $validated['delegate_to'],
            'action' => 'pending',
            'notes' => 'Délégué par ' . Auth::user()->name,
            'validated_at' => null
        ]);

        return redirect()->back()
            ->with('success', 'Demande déléguée avec succès.');
    }
}