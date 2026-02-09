<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Agency::with(['company', 'responsable']);
        
        // Apply intelligent search filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            
            // Split search terms for tag-based search
            $searchTerms = preg_split('/[\s,]+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function($subQuery) use ($term) {
                        $subQuery->where('nom', 'like', "%{$term}%")
                                 ->orWhere('code_unique', 'like', "%{$term}%")
                                 ->orWhere('adresse', 'like', "%{$term}%")
                                 ->orWhere('zone_geographique', 'like', "%{$term}%")
                                 ->orWhere('latitude', 'like', "%{$term}%")
                                 ->orWhere('longitude', 'like', "%{$term}%");
                    });
                }
            });
        }
        
        // Apply company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('statut', $request->get('status'));
        }
        
        // Apply responsible filter
        if ($request->filled('responsable_id')) {
            $query->where('responsable_id', $request->get('responsable_id'));
        }
        
        $agencies = $query->paginate(10)->appends($request->except('page'));
        
        // Get filter options
        $companies = Company::all(); 
        $users = User::all();
        
        return view('agencies.index', compact('agencies', 'companies', 'users'));
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        $users = User::all();
        
        // If a company_id is provided in the request, pre-select it
        $companyId = $request->get('company_id');
        
        return view('agencies.create', compact('companies', 'users', 'companyId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_unique' => 'required|string|max:255|unique:agencies',
            'adresse' => 'required|string',
            'responsable_id' => 'required|exists:users,id',
            'zone_geographique' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'company_id' => 'required|exists:companies,id',
            'statut' => 'required|in:active,en veille'
        ]);

        // Check if validation workflow is required
        $workflow = \App\Models\ValidationWorkflow::where('module', 'agencies')
            ->where('entity_type', 'App\\Models\\Agency')
            ->where('company_id', auth()->user()->company_id)
            ->active()
            ->first();

        if ($workflow) {
            // Create agency with pending status
            $validated['statut'] = 'en veille'; // Set to inactive until validated
            $agency = Agency::create($validated);
            
            // Create validation request
            $validationRequest = $workflow->createValidationRequest($agency, auth()->id(), 'Création de nouvelle agence');
            
            // Log the creation with validation request
            $agency->logAuditTrail('created_pending_validation', 'Agence créée et en attente de validation', [
                'validation_request_id' => $validationRequest->id
            ]);
            
            return redirect()->route('agencies.index')
                ->with('success', 'Agence créée et en attente de validation par le Directeur Général.');
        } else {
            // Create agency directly without validation
            $agency = Agency::create($validated);
            
            // Log the creation
            $agency->logAuditTrail('created', 'Agence créée avec succès');

            return redirect()->route('agencies.index')
                ->with('success', 'Agence créée avec succès.');
        }
    }

    public function edit(Agency $agency)
    {
        $companies = Company::all();
        $users = User::all();
        return view('agencies.edit', compact('agency', 'companies', 'users'));
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_unique' => 'required|string|max:255|unique:agencies,code_unique,' . $agency->id,
            'adresse' => 'required|string',
            'responsable_id' => 'required|exists:users,id',
            'zone_geographique' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'company_id' => 'required|exists:companies,id',
            'statut' => 'required|in:active,en veille'
        ]);

        $agency->update($validated);
        
        // Log the update
        $agency->logAuditTrail('updated', 'Agence mise à jour avec succès');

        return redirect()->route('agencies.index')
            ->with('success', 'Agence mise à jour avec succès.');
    }

    public function destroy(Agency $agency)
    {
        // Check if agency has cash registers
        if ($agency->cashRegisters()->count() > 0) {
            return redirect()->route('agencies.index')
                ->with('error', 'Impossible de supprimer cette agence car elle a des caisses.');
        }
        
        // Log the deletion
        $agency->logAuditTrail('deleted', 'Agence supprimée');
        
        $agency->delete();
        return redirect()->route('agencies.index')
            ->with('success', 'Agence supprimée avec succès.');
    }
    
    // Archive an agency instead of deleting it
    public function archive(Agency $agency)
    {
        $newStatus = $agency->statut === 'active' ? 'en veille' : 'active';
        $agency->update(['statut' => $newStatus]);
        
        $action = $newStatus === 'active' ? 'reactivated' : 'archived';
        $message = $newStatus === 'active' ? 'Agence réactivée avec succès.' : 'Agence archivée avec succès.';
        
        // Log the archive/reactivation
        $agency->logAuditTrail($action, $message);
        
        return redirect()->route('agencies.index')
            ->with('success', $message);
    }
    
    // Duplicate an agency
    public function duplicate(Agency $agency)
    {
        // Create a new agency with the same attributes
        $newAgency = $agency->replicate();
        $newAgency->nom = $agency->nom . ' (Copie)'; 
        $newAgency->code_unique = $agency->code_unique . '_COPY_' . time();
        $newAgency->save();
        
        // Log the duplication
        $newAgency->logAuditTrail('duplicated', 'Agence dupliquée à partir de ' . $agency->nom, [
            'original_agency_id' => $agency->id,
            'original_agency_name' => $agency->nom
        ]);
        
        return redirect()->route('agencies.edit', $newAgency)
            ->with('success', 'Agence dupliquée avec succès.');
    }
    
    public function show(Agency $agency)
    {
        return redirect()->route('companies.dashboard.agency', $agency->id);
    }
}