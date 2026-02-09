<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransaction::with(['cashRegister.entity', 'user', 'validateur'])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');
            
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('numero_transaction', 'like', "%{$search}%")
                  ->orWhere('libelle', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [
                $request->input('date_debut') . ' 00:00:00',
                $request->input('date_fin') . ' 23:59:59'
            ]);
        }
        
        $transactions = $query->paginate(10);
        
        // Get filter options
        $natures = \App\Models\TransactionNature::where('actif', true)->get();
        $companies = \App\Models\Company::all();
        $agencies = \App\Models\Agency::all();
        $users = \App\Models\User::all();
        $statuses = ['pending', 'approved', 'rejected', 'validated'];
        
        return view('cash.transactions.index', compact('transactions', 'natures', 'companies', 'agencies', 'users', 'statuses'));
    }

    public function create(CashRegister $cashRegister)
    {
        if (!$cashRegister->est_ouverte) {
            return redirect()->route('cash.registers.show', ['cashRegister' => $cashRegister->id])
                ->with('error', 'La caisse doit être ouverte pour effectuer des transactions.');
        }
        
        $natures = \App\Models\TransactionNature::where('actif', true)->get();
        return view('cash.transactions.create', compact('cashRegister', 'natures'));
    }

    public function store(Request $request, CashRegister $cashRegister)
    {
        if (!$cashRegister->est_ouverte) {
            return redirect()->route('cash.registers.show', ['cashRegister' => $cashRegister->id])
                ->with('error', 'La caisse doit être ouverte pour effectuer des transactions.');
        }
        
        $session = $cashRegister->currentSession();
        if (!$session) {
            return redirect()->route('cash.registers.show', ['cashRegister' => $cashRegister->id])
                ->with('error', 'Aucune session de caisse ouverte.');
        }
        
        $validated = $request->validate([
            'type' => 'required|in:encaissement,decaissement',
            'montant' => 'required|numeric|min:0.01',
            'libelle' => 'required|string|max:255',
            'nature_operation' => 'required|string|max:255',
            'mode_paiement' => 'required|in:especes,cheque,mobile_money,virement',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'projet' => 'nullable|string|max:255',
            'champs_personnalises' => 'nullable|array'
        ]);
        
        $transaction = new CashTransaction([
            'cash_register_id' => $cashRegister->id,
            'cash_session_id' => $session->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'montant' => $validated['montant'],
            'libelle' => $validated['libelle'],
            'nature_operation' => $validated['nature_operation'],
            'mode_paiement' => $validated['mode_paiement'],
            'projet' => $validated['projet'] ?? null,
            'champs_personnalises' => $validated['champs_personnalises'] ?? null,
            'status' => 'pending'
        ]);
        
        // Générer un numéro de transaction unique
        $transaction->numero_transaction = $transaction->generateTransactionNumber();
        
        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')->store('justificatifs/transactions', 'public');
            $transaction->justificatif = $path;
        }
        
        $transaction->save();
        
        // Créer une demande de validation si nécessaire
        $this->createValidationRequest($transaction, $request);
        
        // Mettre à jour le solde de la caisse
        $nouveauSolde = $cashRegister->solde_actuel;
        if ($validated['type'] === 'encaissement') {
            $nouveauSolde += $validated['montant'];
        } else {
            $nouveauSolde -= $validated['montant'];
        }
        
        $cashRegister->update(['solde_actuel' => $nouveauSolde]);
        
        return redirect()->route('cash.registers.show', ['cashRegister' => $cashRegister->id])
            ->with('success', 'Transaction enregistrée avec succès.');
    }

    public function show(CashTransaction $transaction)
    {
        $transaction->load(['cashRegister.entity', 'user', 'validateur', 'cashSession', 'validationRequest']);
        return view('cash.transactions.show', compact('transaction'));
    }

    public function validateTransaction(Request $request, CashTransaction $transaction)
    {
        if ($transaction->isValidated()) {
            return redirect()->route('cash.transactions.show', $transaction)
                ->with('error', 'Cette transaction est déjà validée.');
        }
        
        $transaction->update([
            'validateur_id' => Auth::id(),
            'date_validation' => now()
        ]);
        
        return redirect()->route('cash.transactions.show', $transaction)
            ->with('success', 'Transaction validée avec succès.');
    }

    public function approve(Request $request, CashTransaction $transaction)
    {
        // Check if user can validate this transaction
        if (!Auth::user()->canValidateTransaction($transaction)) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à approuver cette transaction.');
        }
        
        $transaction->update([
            'status' => 'approved',
            'validated_at' => now()
        ]);
        
        // If this transaction has a validation request, approve it too
        if ($transaction->validationRequest) {
            $transaction->validationRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);
        }
        
        return redirect()->back()->with('success', 'Transaction approuvée avec succès.');
    }

    public function reject(Request $request, CashTransaction $transaction)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        // Check if user can validate this transaction
        if (!Auth::user()->canValidateTransaction($transaction)) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rejeter cette transaction.');
        }
        
        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'validated_at' => now()
        ]);
        
        // If this transaction has a validation request, reject it too
        if ($transaction->validationRequest) {
            $transaction->validationRequest->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);
        }
        
        return redirect()->back()->with('success', 'Transaction rejetée avec succès.');
    }

    public function export(Request $request)
    {
        // Implementation for exporting cash journal
        // This would typically generate a PDF or Excel file
        return redirect()->back()->with('info', 'Export de journal de caisse en cours de développement.');
    }

    public function consolidatedMovements(Request $request)
    {
        // Get consolidated movements by company, agency, and nature
        $query = CashTransaction::with(['cashRegister.entity']);
        
        if ($request->has('date_debut') && $request->has('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut . ' 00:00:00', $request->date_fin . ' 23:59:59']);
        }
        
        $transactions = $query->get();
        
        // Group by company
        $movementsByCompany = [];
        foreach ($transactions as $transaction) {
            $entity = $transaction->cashRegister->entity;
            $companyId = $entity->id;
            $companyName = $entity->raison_sociale ?? $entity->nom;
            
            if (!isset($movementsByCompany[$companyId])) {
                $movementsByCompany[$companyId] = [
                    'name' => $companyName,
                    'encaissements' => 0,
                    'decaissements' => 0,
                    'agencies' => []
                ];
            }
            
            if ($transaction->type === 'encaissement') {
                $movementsByCompany[$companyId]['encaissements'] += $transaction->montant;
            } else {
                $movementsByCompany[$companyId]['decaissements'] += $transaction->montant;
            }
        }
        
        return view('cash.reports.consolidated', compact('movementsByCompany'));
    }

    public function exportConsolidatedMovements(Request $request)
    {
        // Implementation for exporting consolidated movements
        return redirect()->back()->with('info', 'Export de mouvements consolidés en cours de développement.');
    }
    
    private function createValidationRequest(CashTransaction $transaction, Request $request)
    {
        // Check if validation is required based on amount or other criteria
        $requiresValidation = $this->requiresValidation($transaction);
        
        if ($requiresValidation) {
            // Find appropriate validation workflow
            $workflow = \App\Models\ValidationWorkflow::where('module', 'cash')
                ->where('entity_type', get_class($transaction))
                ->where('is_active', true)
                ->first();
                
            if ($workflow) {
                // Create validation request
                $validationRequest = new \App\Models\ValidationRequest([
                    'workflow_id' => $workflow->id,
                    'requester_id' => Auth::id(),
                    'entity_type' => get_class($transaction),
                    'entity_id' => $transaction->id,
                    'title' => "Validation de transaction {$transaction->numero_transaction}",
                    'description' => "Transaction {$transaction->type} de {$transaction->montant} XAF",
                    'data' => json_encode([
                        'transaction_id' => $transaction->id,
                        'type' => $transaction->type,
                        'montant' => $transaction->montant,
                        'libelle' => $transaction->libelle,
                        'nature_operation' => $transaction->nature_operation
                    ]),
                    'status' => 'pending',
                    'current_step' => 0
                ]);
                
                $validationRequest->save();
                
                // Link transaction to validation request
                $transaction->update(['validation_request_id' => $validationRequest->id]);
            }
        }
    }
    
    private function requiresValidation(CashTransaction $transaction)
    {
        // For demonstration, require validation for all transactions over 500,000 XAF
        // In a real implementation, this would be configurable
        return $transaction->montant > 500000;
    }
}