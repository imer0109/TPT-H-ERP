<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Company;
use App\Models\Agency;
use App\Models\CashTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    public function index()
    {
        $cashRegisters = CashRegister::with(['entity'])->paginate(10);
        return view('cash.registers.index', compact('cashRegisters'));
    }

    public function create()
    {
        return view('cash.registers.create');
    }

    public function store(Request $request)
    {
        // Afficher les données reçues pour le débogage
        \Log::info('Données reçues dans store:', $request->all());
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'entity_type' => 'required|in:App\Models\Company,App\Models\Agency',
            'entity_id' => 'required|integer',
            'type' => 'required|in:principale,secondaire',
            'solde_initial' => 'required|numeric|min:0',
            'active' => 'sometimes|boolean'
        ]);
        
        // Journaliser les données validées
        \Log::info('Données validées:', $validated);

        // Vérifier que l'entité existe
        $entity = $validated['entity_type']::findOrFail($validated['entity_id']);
        
        // Récupérer la raison sociale en fonction du type d'entité
        $raison_sociale = '';
        if ($validated['entity_type'] === 'App\Models\Company') {
            $raison_sociale = $entity->raison_sociale;
        } else if ($validated['entity_type'] === 'App\Models\Agency') {
            $raison_sociale = $entity->company->raison_sociale ?? $entity->nom;
        }
        
        $cashRegister = new CashRegister([
            'nom' => $validated['nom'],
            'raison_sociale' => $raison_sociale,
            'type' => $validated['type'],
            'solde_actuel' => $validated['solde_initial'],
            'est_ouverte' => false,
            'active' => $request->has('active') ? $request->active : true
        ]);

        $entity->cashRegisters()->save($cashRegister);

        return redirect()->route('cash.registers.index')
            ->with('success', 'Caisse créée avec succès.');
    }

    public function show(CashRegister $cashRegister)
    {
        $cashRegister->load(['entity', 'sessions.user']);
        return view('cash.registers.show', compact('cashRegister'));
    }

    public function edit(CashRegister $cashRegister)
    {
        return view('cash.registers.edit', compact('cashRegister'));
    }

    public function update(Request $request, CashRegister $cashRegister)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'entity_type' => 'required|in:App\Models\Company,App\Models\Agency',
            'entity_id' => 'required|integer',
            'type' => 'required|in:principale,secondaire'
        ]);

        $entity = $validated['entity_type']::findOrFail($validated['entity_id']);
        
        // Récupérer la raison sociale en fonction du type d'entité
        $raison_sociale = '';
        if ($validated['entity_type'] === 'App\Models\Company') {
            $raison_sociale = $entity->raison_sociale;
        } else if ($validated['entity_type'] === 'App\Models\Agency') {
            $raison_sociale = $entity->company->raison_sociale ?? $entity->nom;
        }
        
        $cashRegister->update([
            'nom' => $validated['nom'],
            'raison_sociale' => $raison_sociale,
            'type' => $validated['type'],
            'active' => $request->has('active') ? $request->active : $cashRegister->active
        ]);

        $cashRegister->entity()->associate($entity);
        $cashRegister->save();

        return redirect()->route('cash.registers.index')
            ->with('success', 'Caisse mise à jour avec succès.');
    }

    public function destroy(CashRegister $cashRegister)
    {
        if ($cashRegister->est_ouverte) {
            return redirect()->route('cash.registers.index')
                ->with('error', 'Impossible de supprimer une caisse ouverte.');
        }

        if ($cashRegister->transactions()->exists()) {
            return redirect()->route('cash.registers.index')
                ->with('error', 'Impossible de supprimer une caisse avec des transactions.');
        }

        $cashRegister->delete();

        return redirect()->route('cash.registers.index')
            ->with('success', 'Caisse supprimée avec succès.');
    }

    public function dashboard()
    {
        // Get cash registers with their entities
        $cashRegisters = CashRegister::with(['entity'])->get();
        
        // Get recent transactions
        $recentTransactions = CashTransaction::with(['cashRegister.entity', 'user'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Calculate totals by entity type
        $totalsByCompany = [];
        $totalsByAgency = [];
        
        foreach ($cashRegisters as $cashRegister) {
            if ($cashRegister->entity_type === 'App\Models\Company') {
                if (!isset($totalsByCompany[$cashRegister->entity_id])) {
                    $totalsByCompany[$cashRegister->entity_id] = [
                        'name' => $cashRegister->entity->raison_sociale,
                        'total' => 0
                    ];
                }
                $totalsByCompany[$cashRegister->entity_id]['total'] += $cashRegister->solde_actuel;
            } else if ($cashRegister->entity_type === 'App\Models\Agency') {
                $companyId = $cashRegister->entity->company_id;
                if (!isset($totalsByCompany[$companyId])) {
                    $totalsByCompany[$companyId] = [
                        'name' => $cashRegister->entity->company->raison_sociale,
                        'total' => 0
                    ];
                }
                $totalsByCompany[$companyId]['total'] += $cashRegister->solde_actuel;
                
                if (!isset($totalsByAgency[$cashRegister->entity_id])) {
                    $totalsByAgency[$cashRegister->entity_id] = [
                        'name' => $cashRegister->entity->nom,
                        'company' => $cashRegister->entity->company->raison_sociale,
                        'total' => 0
                    ];
                }
                $totalsByAgency[$cashRegister->entity_id]['total'] += $cashRegister->solde_actuel;
            }
        }
        
        // Get alerts (low funds, anomalies)
        $alerts = [];
        foreach ($cashRegisters as $cashRegister) {
            $entityName = $cashRegister->entity->raison_sociale ?? ($cashRegister->entity->nom ?? 'N/A');
            if ($cashRegister->solde_actuel < 10000) { // Alert if less than 10,000
                $alerts[] = [
                    'type' => 'low_funds',
                    'message' => "Fonds insuffisants dans la caisse {$cashRegister->nom} ({$entityName})",
                    'amount' => $cashRegister->solde_actuel
                ];
            }
        }
        
        return view('cash.dashboard', compact('cashRegisters', 'recentTransactions', 'totalsByCompany', 'totalsByAgency', 'alerts'));
    }
}
