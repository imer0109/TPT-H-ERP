<?php

namespace App\Http\Controllers;
use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\TransactionNature;
use App\Models\Company;
use App\Models\Agency;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransaction::with(['cashRegister.entity', 'user', 'validateur']);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('date_debut') && $request->has('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut . ' 00:00:00', $request->date_fin . ' 23:59:59']);
        }
        
        if ($request->has('entity_id') && $request->has('entity_type')) {
            $query->whereHas('cashRegister', function($q) use ($request) {
                $q->where('entity_id', $request->entity_id)
                  ->where('entity_type', $request->entity_type);
            });
        }
        
        // Filtre par nature d'opération
        if ($request->has('nature_operation') && $request->nature_operation) {
            $query->where('nature_operation', $request->nature_operation);
        }
        
        // Filtre par société
        if ($request->has('company_id') && $request->company_id) {
            $query->whereHas('cashRegister', function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $query->where('entity_type', 'App\\Models\\Company')
                          ->where('entity_id', $request->company_id);
                    
                    // Inclure également les agences de cette société
                    $query->orWhereHas('entity', function($agencyQuery) use ($request) {
                        $agencyQuery->where('entity_type', 'App\\Models\\Agency')
                                   ->whereHas('company', function($companyQuery) use ($request) {
                                       $companyQuery->where('id', $request->company_id);
                                   });
                    });
                });
            });
        }
        
        // Filtre par agence
        if ($request->has('agency_id') && $request->agency_id) {
            $query->whereHas('cashRegister', function($q) use ($request) {
                $q->where('entity_type', 'App\\Models\\Agency')
                  ->where('entity_id', $request->agency_id);
            });
        }
        
        // Filtre par utilisateur (auteur)
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        // Récupérer les données pour les filtres
        $natures = TransactionNature::where('actif', true)->get();
        $companies = Company::orderBy('raison_sociale')->get();
        $agencies = Agency::orderBy('nom')->get();
        $users = User::orderBy('nom')->get();
        
        return view('cash.transactions.index', compact('transactions', 'natures', 'companies', 'agencies', 'users'));
    }

    public function create(CashRegister $cashRegister)
    {
        if (!$cashRegister->est_ouverte) {
            return redirect()->route('cash.registers.show', $cashRegister)
                ->with('error', 'La caisse doit être ouverte pour effectuer des transactions.');
        }
        
        $natures = TransactionNature::where('actif', true)->get();
        return view('cash.transactions.create', compact('cashRegister', 'natures'));
    }

    public function store(Request $request, CashRegister $cashRegister)
    {
        if (!$cashRegister->est_ouverte) {
            return redirect()->route('cash.registers.show', $cashRegister)
                ->with('error', 'La caisse doit être ouverte pour effectuer des transactions.');
        }
        
        $session = $cashRegister->currentSession();
        if (!$session) {
            return redirect()->route('cash.registers.show', $cashRegister)
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
            'champs_personnalises' => $validated['champs_personnalises'] ?? null
        ]);
        
        // Générer un numéro de transaction unique
        $transaction->numero_transaction = $transaction->generateTransactionNumber();
        
        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')->store('justificatifs/transactions', 'public');
            $transaction->justificatif = $path;
        }
        
        $transaction->save();
        
        // Mettre à jour le solde de la caisse
        $nouveauSolde = $cashRegister->solde_actuel;
        if ($validated['type'] === 'encaissement') {
            $nouveauSolde += $validated['montant'];
        } else {
            $nouveauSolde -= $validated['montant'];
        }
        
        $cashRegister->update(['solde_actuel' => $nouveauSolde]);
        
        return redirect()->route('cash.registers.show', $cashRegister)
            ->with('success', 'Transaction enregistrée avec succès.');
    }

    public function show(CashTransaction $transaction)
    {
        $transaction->load(['cashRegister.entity', 'user', 'validateur', 'cashSession']);
        return view('cash.transactions.show', compact('transaction'));
    }

    public function validate(Request $request, CashTransaction $transaction)
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
}
