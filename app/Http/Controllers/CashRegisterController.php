<?php

namespace App\Http\Controllers;
use App\Models\CashRegister;
use App\Models\Company;
use App\Models\Agency;
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
        $societes = Company::all();
        $agences = Agency::all();
        return view('cash.registers.create', compact('societes', 'agences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'entity_type' => 'required|in:App\\Models\\Company,App\\Models\\Agency',
            'entity_id' => 'required|integer',
            'type' => 'required|in:principale,secondaire',
            'solde_actuel' => 'required|numeric|min:0'
        ]);

        $cashRegister = new CashRegister([
            'nom' => $validated['nom'],
            'type' => $validated['type'],
            'solde_actuel' => $validated['solde_actuel'],
            'est_ouverte' => false
        ]);

        $entity = $validated['entity_type']::findOrFail($validated['entity_id']);
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
        $societes = Company::all();
        $agences = Agency::all();
        return view('cash.registers.edit', compact('cashRegister', 'societes', 'agences'));
    }

    public function update(Request $request, CashRegister $cashRegister)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'entity_type' => 'required|in:App\\Models\\Company,App\\Models\\Agency',
            'entity_id' => 'required|integer',
            'type' => 'required|in:principale,secondaire'
        ]);

        $cashRegister->update([
            'nom' => $validated['nom'],
            'type' => $validated['type']
        ]);

        $entity = $validated['entity_type']::findOrFail($validated['entity_id']);
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
}
