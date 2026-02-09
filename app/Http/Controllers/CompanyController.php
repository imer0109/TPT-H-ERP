<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with('parent');
        
        // Apply intelligent search filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            
            // Split search terms for tag-based search
            $searchTerms = preg_split('/[\s,]+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function($subQuery) use ($term) {
                        $subQuery->where('raison_sociale', 'like', "%{$term}%")
                                 ->orWhere('niu', 'like', "%{$term}%")
                                 ->orWhere('rccm', 'like', "%{$term}%")
                                 ->orWhere('pays', 'like', "%{$term}%")
                                 ->orWhere('ville', 'like', "%{$term}%")
                                 ->orWhere('secteur_activite', 'like', "%{$term}%")
                                 ->orWhere('devise', 'like', "%{$term}%")
                                 ->orWhere('email', 'like', "%{$term}%")
                                 ->orWhere('telephone', 'like', "%{$term}%")
                                 ->orWhere('whatsapp', 'like', "%{$term}%")
                                 ->orWhere('site_web', 'like', "%{$term}%");
                    });
                }
            });
        }
        
        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('active', $request->get('status') === 'active');
        }
        
        // Apply sector filter
        if ($request->filled('sector')) {
            $query->where('secteur_activite', $request->get('sector'));
        }
        
        // Apply country filter
        if ($request->filled('country')) {
            $query->where('pays', $request->get('country'));
        }
        
        $companies = $query->paginate(10)->appends($request->except('page'));
        
        // Get filter options
        $sectors = Company::select('secteur_activite')->distinct()->pluck('secteur_activite');
        $countries = Company::select('pays')->distinct()->pluck('pays');
        
        return view('companies.index', compact('companies', 'sectors', 'countries'));
    }

    public function create(Request $request)
    {
        // Get all holding companies for parent selection
        $holdings = Company::holdings()->get();
        
        // If a parent_id is provided in the request, pre-select it
        $parentId = $request->get('parent_id');
        
        return view('companies.create', compact('holdings', 'parentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|in:holding,filiale',
            'niu' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:255',
            'regime_fiscal' => 'nullable|string|max:255',
            'secteur_activite' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'siege_social' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'parent_id' => 'nullable|exists:companies,id',
            'logo' => 'nullable|image|max:2048',
            'visuel' => 'nullable|image|max:2048'
        ]);

        // Handle logo upload with error handling
        if ($request->hasFile('logo')) {
            try {
                $path = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['logo' => 'Le logo n\'a pas pu être téléchargé. Veuillez réessayer.']);
            }
        }

        // Handle visuel upload with error handling
        if ($request->hasFile('visuel')) {
            try {
                $path = $request->file('visuel')->store('visuels', 'public');
                $validated['visuel'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['visuel' => 'Le visuel n\'a pas pu être téléchargé. Veuillez réessayer.']);
            }
        }

        // Set default active status
        $validated['active'] = true;

        // Check if validation workflow is required
        $workflow = \App\Models\ValidationWorkflow::where('module', 'companies')
            ->where('entity_type', 'App\\Models\\Company')
            ->where('company_id', auth()->user()->company_id)
            ->active()
            ->first();

        if ($workflow) {
            // Create company with pending status
            $validated['active'] = false; // Set to inactive until validated
            $company = Company::create($validated);
            
            // Create validation request
            $validationRequest = $workflow->createValidationRequest($company, auth()->id(), 'Création de nouvelle société');
            
            // Log the creation with validation request
            $company->logAuditTrail('created_pending_validation', 'Société créée et en attente de validation', [
                'validation_request_id' => $validationRequest->id
            ]);
            
            return redirect()->route('companies.index')
                ->with('success', 'Société créée et en attente de validation par le Directeur Général.');
        } else {
            // Create company directly without validation
            $company = Company::create($validated);
            
            // Log the creation
            $company->logAuditTrail('created', 'Société créée avec succès');

            return redirect()->route('companies.index')
                ->with('success', 'Société créée avec succès.');
        }
    }

    public function edit(Company $company)
    {
        // Get all holding companies except the current one for parent selection
        $holdings = Company::holdings()
            ->where('id', '!=', $company->id)
            ->get();
            
        return view('companies.edit', compact('company', 'holdings'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|in:holding,filiale',
            'niu' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:255',
            'regime_fiscal' => 'nullable|string|max:255',
            'secteur_activite' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'siege_social' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'parent_id' => 'nullable|exists:companies,id',
            'logo' => 'nullable|image|max:2048',
            'visuel' => 'nullable|image|max:2048',
            'active' => 'nullable|boolean'
        ]);

        // Handle logo upload with error handling
        if ($request->hasFile('logo')) {
            try {
                $path = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['logo' => 'Le logo n\'a pas pu être téléchargé. Veuillez réessayer.']);
            }
        }

        // Handle visuel upload with error handling
        if ($request->hasFile('visuel')) {
            try {
                $path = $request->file('visuel')->store('visuels', 'public');
                $validated['visuel'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['visuel' => 'Le visuel n\'a pas pu être téléchargé. Veuillez réessayer.']);
            }
        }

        $company->update($validated);
        
        // Log the update
        $company->logAuditTrail('updated', 'Société mise à jour avec succès');

        return redirect()->route('companies.index')
            ->with('success', 'Société mise à jour avec succès.');
    }

    public function destroy(Company $company)
    {
        // Check if company has subsidiaries or agencies
        if ($company->filiales()->count() > 0) {
            return redirect()->route('companies.index')
                ->with('error', 'Impossible de supprimer cette société car elle a des filiales.');
        }
        
        if ($company->agencies()->count() > 0) {
            return redirect()->route('companies.index')
                ->with('error', 'Impossible de supprimer cette société car elle a des agences.');
        }
        
        // Log the deletion
        $company->logAuditTrail('deleted', 'Société supprimée');
        
        $company->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Société supprimée avec succès.');
    }
    
    // Archive a company instead of deleting it
    public function archive(Company $company)
    {
        $company->update(['active' => !$company->active]);
        
        $action = $company->active ? 'reactivated' : 'archived';
        $message = $company->active ? 'Société réactivée avec succès.' : 'Société archivée avec succès.';
        
        // Log the archive/reactivation
        $company->logAuditTrail($action, $message);
        
        return redirect()->route('companies.index')
            ->with('success', $message);
    }
    
    // Duplicate a company
    public function duplicate(Company $company)
    {
        // Create a new company with the same attributes
        $newCompany = $company->replicate();
        $newCompany->raison_sociale = $company->raison_sociale . ' (Copie)';
        $newCompany->save();
        
        // Log the duplication
        $newCompany->logAuditTrail('duplicated', 'Société dupliquée à partir de ' . $company->raison_sociale, [
            'original_company_id' => $company->id,
            'original_company_name' => $company->raison_sociale
        ]);
        
        return redirect()->route('companies.edit', $newCompany)
            ->with('success', 'Société dupliquée avec succès.');
    }
    
    public function show(Company $company)
    {
        return redirect()->route('companies.dashboard.company', $company);
    }
}