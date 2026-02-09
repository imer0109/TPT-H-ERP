<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::with('employee');
        
        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $contracts = $query->latest()->paginate(15);
        $employees = Employee::orderBy('last_name')->get();
        
        return view('contracts.index', compact('contracts', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::orderBy('last_name')->get();
        return view('contracts.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:CDI,CDD,Stage,Prestation,Intérim',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'trial_period_start' => 'nullable|date|after_or_equal:start_date',
            'trial_period_end' => 'nullable|date|after:trial_period_start',
            'base_salary' => 'required|numeric|min:0',
            'benefits' => 'nullable|string',
            'status' => 'required|in:draft,pending,active',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'hiring_form' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'supporting_documents' => 'nullable|array',
            'supporting_documents.*' => 'file|mimes:pdf,doc,docx|max:2048'
        ]);
        
        // Handle trial period calculation
        if (isset($validated['trial_period_start']) && isset($validated['trial_period_end'])) {
            // Trial period is already set
        } else if ($request->filled('trial_period_months')) {
            // Calculate trial period end based on months
            $trialPeriodStart = $validated['start_date'];
            $trialPeriodEnd = \Carbon\Carbon::parse($trialPeriodStart)->addMonths($request->trial_period_months);
            $validated['trial_period_start'] = $trialPeriodStart;
            $validated['trial_period_end'] = $trialPeriodEnd;
        }
        
        // Handle file uploads
        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }
        
        if ($request->hasFile('hiring_form')) {
            $validated['hiring_form'] = $request->file('hiring_form')->store('contracts/hiring_forms', 'public');
        }
        
        // Handle supporting documents
        if ($request->hasFile('supporting_documents')) {
            $supportingDocs = [];
            foreach ($request->file('supporting_documents') as $file) {
                $supportingDocs[] = $file->store('contracts/supporting', 'public');
            }
            $validated['supporting_documents'] = $supportingDocs;
        }
        
        $contract = Contract::create($validated);
        
        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $contract->load('employee', 'terminatedBy');
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $employees = Employee::orderBy('last_name')->get();
        return view('contracts.edit', compact('contract', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:CDI,CDD,Stage,Prestation,Intérim',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'trial_period_start' => 'nullable|date|after_or_equal:start_date',
            'trial_period_end' => 'nullable|date|after:trial_period_start',
            'base_salary' => 'required|numeric|min:0',
            'benefits' => 'nullable|string',
            'status' => 'required|in:draft,pending,active,terminated',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'hiring_form' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'supporting_documents' => 'nullable|array',
            'supporting_documents.*' => 'file|mimes:pdf,doc,docx|max:2048'
        ]);
        
        // Handle trial period calculation
        if (isset($validated['trial_period_start']) && isset($validated['trial_period_end'])) {
            // Trial period is already set
        } else if ($request->filled('trial_period_months')) {
            // Calculate trial period end based on months
            $trialPeriodStart = $validated['start_date'];
            $trialPeriodEnd = \Carbon\Carbon::parse($trialPeriodStart)->addMonths($request->trial_period_months);
            $validated['trial_period_start'] = $trialPeriodStart;
            $validated['trial_period_end'] = $trialPeriodEnd;
        }
        
        // Handle file uploads
        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        } else {
            // Keep existing file if not uploading a new one
            unset($validated['contract_file']);
        }
        
        if ($request->hasFile('hiring_form')) {
            $validated['hiring_form'] = $request->file('hiring_form')->store('contracts/hiring_forms', 'public');
        } else {
            // Keep existing file if not uploading a new one
            unset($validated['hiring_form']);
        }
        
        // Handle supporting documents
        if ($request->hasFile('supporting_documents')) {
            $supportingDocs = [];
            foreach ($request->file('supporting_documents') as $file) {
                $supportingDocs[] = $file->store('contracts/supporting', 'public');
            }
            $validated['supporting_documents'] = $supportingDocs;
        }
        
        $contract->update($validated);
        
        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contrat mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        
        return redirect()->route('contracts.index')
            ->with('success', 'Contrat supprimé avec succès.');
    }
    
    /**
     * Terminate a contract
     */
    public function terminate(Request $request, Contract $contract)
    {
        // Only allow termination of active contracts
        if ($contract->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Seuls les contrats actifs peuvent être résiliés.');
        }
        
        $validated = $request->validate([
            'termination_date' => 'required|date|after_or_equal:today',
            'termination_reason' => 'required|string|max:500'
        ]);
        
        $contract->update([
            'status' => 'terminated',
            'terminated_at' => $validated['termination_date'],
            'termination_reason' => $validated['termination_reason'],
            'terminated_by' => Auth::id()
        ]);
        
        return redirect()->back()
            ->with('success', 'Contrat résilié avec succès.');
    }
    
    /**
     * Renew a contract
     */
    public function renew(Request $request, Contract $contract)
    {
        // Only allow renewal of active contracts that are about to expire
        if ($contract->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Seuls les contrats actifs peuvent être renouvelés.');
        }
        
        if ($contract->end_date && $contract->end_date->diffInDays(now()) > 30) {
            return redirect()->back()
                ->with('error', 'Le contrat ne peut être renouvelé que dans les 30 jours précédant son expiration.');
        }
        
        $validated = $request->validate([
            'new_end_date' => 'required|date|after:today',
            'new_salary' => 'nullable|numeric|min:0'
        ]);
        
        $contract->update([
            'end_date' => $validated['new_end_date'],
            'base_salary' => $validated['new_salary'] ?? $contract->base_salary
        ]);
        
        return redirect()->back()
            ->with('success', 'Contrat renouvelé avec succès.');
    }
    
    /**
     * Activate a contract
     */
    public function activate(Contract $contract)
    {
        // Only allow activation of pending or draft contracts
        if (!in_array($contract->status, ['pending', 'draft'])) {
            return redirect()->back()
                ->with('error', 'Seuls les contrats en attente ou brouillons peuvent être activés.');
        }
        
        $contract->update([
            'status' => 'active',
            'start_date' => now() // Set start date to today if not already set
        ]);
        
        return redirect()->back()
            ->with('success', 'Contrat activé avec succès.');
    }
}
