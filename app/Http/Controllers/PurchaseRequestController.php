<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Department;
use App\Models\Company;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseRequest::with(['requester', 'department', 'company']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('objet', 'LIKE', "%{$search}%")
                  ->orWhere('numero_da', 'LIKE', "%{$search}%")
                  ->orWhereHas('requester', function($qr) use ($search) {
                      $qr->where('nom', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('statut', $request->status);
        }
        
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }
        
        $purchaseRequests = $query->latest()->paginate(15);
        $departments = Department::orderBy('name')->get();
        $companies = Company::orderBy('raison_sociale')->get();
        $agencies = Agency::orderBy('nom')->get();
        $statuts = PurchaseRequest::STATUTS;
        $natures = PurchaseRequest::NATURE_ACHATS;
        
        return view('purchases.requests.index', compact('purchaseRequests', 'departments', 'companies', 'agencies', 'statuts', 'natures'));
    }
    
    public function create()
    {
        // Check permission
        $this->authorize('create', PurchaseRequest::class);
        
        $departments = Department::orderBy('name')->get();
        $agencies = Agency::orderBy('nom')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();
        
        return view('purchases.requests.create', compact('departments', 'agencies', 'users'));
    }
    
    public function store(Request $request)
    {
        // Check permission
        $this->authorize('create', PurchaseRequest::class);
        
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'agency_id' => 'required|exists:agencies,id',
            'requester_id' => 'required|exists:users,id',
            'date_request' => 'required|date',
            'objet' => 'required|string|max:500',
            'justification' => 'nullable|string',
            'priority' => 'required|in:faible,moyenne,haute,urgence',
            'required_date' => 'nullable|date|after_or_equal:date_request',
        ]);

        $validated['reference'] = $this->generateReference();
        $validated['statut'] = 'en_attente_validation';
        $validated['created_by'] = Auth::id();

        $purchaseRequest = PurchaseRequest::create($validated);

        return redirect()->route('purchases.requests.show', $purchaseRequest)
            ->with('success', 'Demande d\'achat créée avec succès.');
    }

    /**
     * Display the specified purchase request.
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        // Check permission
        $this->authorize('view', $purchaseRequest);
        
        $purchaseRequest->load(['department', 'agency', 'requester', 'items.product', 'validations.user']);
        
        return view('purchases.requests.show', compact('purchaseRequest'));
    }

    /**
     * Show the form for editing the specified purchase request.
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        // Check permission
        $this->authorize('edit', $purchaseRequest);
        
        $departments = \App\Models\Department::orderBy('name')->get();
        $agencies = Agency::orderBy('nom')->get();
        $users = User::orderBy('nom')->orderBy('prenom')->get();
        
        $purchaseRequest->load(['department', 'agency', 'requester']);
        
        return view('purchases.requests.edit', compact('purchaseRequest', 'departments', 'agencies', 'users'));
    }
    
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->statut !== 'Brouillon') {
            return redirect()->route('purchases.requests.show', $purchaseRequest)
                ->with('error', 'Seules les demandes en brouillon peuvent être modifiées.');
        }
        
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
            'date_souhaitee' => 'required|date|after:today',
            'urgence' => 'required|in:Normale,Urgente,Très urgente',
            'famille_articles' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.designation' => 'required|string|max:255',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.unite' => 'required|string|max:50',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
            'items.*.fournisseur_suggere' => 'nullable|string|max:255'
        ]);
        
        DB::beginTransaction();
        
        try {
            $purchaseRequest->update([
                'company_id' => $validated['company_id'],
                'department_id' => $validated['department_id'],
                'objet' => $validated['objet'],
                'description' => $validated['description'],
                'date_souhaitee' => $validated['date_souhaitee'],
                'urgence' => $validated['urgence'],
                'famille_articles' => $validated['famille_articles'],
                'prix_estime_total' => collect($validated['items'])->sum(function($item) {
                    return $item['quantite'] * $item['prix_unitaire'];
                })
            ]);
            
            // Delete existing items
            $purchaseRequest->items()->delete();
            
            // Create new items
            foreach ($validated['items'] as $itemData) {
                $purchaseRequest->items()->create($itemData);
            }
            
            DB::commit();
            
            return redirect()->route('purchases.requests.show', $purchaseRequest)
                ->with('success', 'Demande d\'achat mise à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de la demande.'])->withInput();
        }
    }
    
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->statut !== 'Brouillon') {
            return redirect()->route('purchases.requests.show', $purchaseRequest)
                ->with('error', 'Seules les demandes en brouillon peuvent être supprimées.');
        }
        
        $purchaseRequest->delete();
        
        return redirect()->route('purchases.requests.index')
            ->with('success', 'Demande d\'achat supprimée avec succès.');
    }
    
    public function validateRequest(Request $request, PurchaseRequest $purchaseRequest)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'commentaires' => 'nullable|string'
        ]);
        
        if (!$purchaseRequest->canBeValidated()) {
            return redirect()->route('purchases.requests.show', $purchaseRequest)
                ->with('error', 'Cette demande ne peut pas être validée.');
        }
        
        DB::beginTransaction();
        
        try {
            if ($validated['action'] === 'approve') {
                $purchaseRequest->update([
                    'statut' => 'Validée',
                    'validated_by' => Auth::id(),
                    'validated_at' => now(),
                    'validation_comments' => $validated['commentaires']
                ]);
                
                // Mettre à jour les validations en attente
                $purchaseRequest->validations()
                    ->where('statut', 'En attente')
                    ->where('validated_by', Auth::id())
                    ->update([
                        'statut' => 'Approuvée',
                        'commentaires' => $validated['commentaires'],
                        'validated_at' => now()
                    ]);
                
                $message = 'Demande d\'achat approuvée avec succès.';
            } else {
                $purchaseRequest->update([
                    'statut' => 'Refusée',
                    'validated_by' => Auth::id(),
                    'validated_at' => now(),
                    'validation_comments' => $validated['commentaires']
                ]);
                
                // Mettre à jour les validations en attente
                $purchaseRequest->validations()
                    ->where('statut', 'En attente')
                    ->where('validated_by', Auth::id())
                    ->update([
                        'statut' => 'Rejetée',
                        'commentaires' => $validated['commentaires'],
                        'validated_at' => now()
                    ]);
                
                $message = 'Demande d\'achat rejetée avec succès.';
            }
            
            DB::commit();
            
            return redirect()->route('purchases.requests.show', $purchaseRequest)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('purchases.requests.show', $purchaseRequest)
                ->with('error', 'Une erreur est survenue lors de la validation de la demande.');
        }
    }
    
    private function createValidationWorkflow(PurchaseRequest $purchaseRequest)
    {
        // This would contain logic to create the validation workflow
        // For now, we'll just create a simple validation request
        $validationRequest = $purchaseRequest->validationRequest()->create([
            'requested_by' => $purchaseRequest->requested_by,
            'type' => 'purchase_request',
            'status' => 'pending'
        ]);
        
        // Create validation steps (simplified)
        $validationRequest->steps()->create([
            'step_number' => 1,
            'validator_id' => $purchaseRequest->requested_by, // Would be department head in real implementation
            'status' => 'pending',
            'required_approvals' => 1
        ]);
    }
}