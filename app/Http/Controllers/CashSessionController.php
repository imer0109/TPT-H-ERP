<?php

namespace App\Http\Controllers;
use App\Models\CashRegister;
use App\Models\CashSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashSessionController extends Controller
{
     public function open(Request $request, CashRegister $cashRegister)
    {
        if ($cashRegister->est_ouverte) {
            return redirect()->route('cash.registers.show', $cashRegister)
                ->with('error', 'Cette caisse est déjà ouverte.');
        }

        $validated = $request->validate([
            'solde_initial' => 'required|numeric|min:0',
            'commentaire' => 'nullable|string'
        ]);

        $session = new CashSession([
            'user_id' => Auth::id(),
            'solde_initial' => $validated['solde_initial'],
            'date_ouverture' => now(),
            'commentaire' => $validated['commentaire'] ?? null
        ]);

        $cashRegister->sessions()->save($session);
        $cashRegister->update([
            'est_ouverte' => true,
            'solde_actuel' => $validated['solde_initial']
        ]);

        return redirect()->route('cash.registers.show', $cashRegister)
            ->with('success', 'Caisse ouverte avec succès.');
    }

    public function close(Request $request, CashRegister $cashRegister, CashSession $session)
    {
        if (!$cashRegister->est_ouverte || !$session->isOpen()) {
            return redirect()->route('cash.registers.show', $cashRegister)
                ->with('error', 'Cette session de caisse est déjà fermée.');
        }

        $validated = $request->validate([
            'solde_final' => 'required|numeric|min:0',
            'commentaire' => 'nullable|string',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $soldeCalcule = $session->calculateBalance();
        $difference = abs($soldeCalcule - $validated['solde_final']);

        if ($difference > 0.01) { // Tolérance pour les erreurs d'arrondi
            $request->session()->flash('warning', 'Attention : Le solde final déclaré diffère du solde calculé de ' . number_format($difference, 2) . '.');
        }

        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')->store('justificatifs/sessions', 'public');
            $session->justificatif_fermeture = $path;
        }

        $session->update([
            'solde_final' => $validated['solde_final'],
            'date_fermeture' => now(),
            'commentaire' => $validated['commentaire'] ?? $session->commentaire
        ]);

        $cashRegister->update([
            'est_ouverte' => false,
            'solde_actuel' => $validated['solde_final']
        ]);

        return redirect()->route('cash.registers.show', $cashRegister)
            ->with('success', 'Caisse fermée avec succès.');
    }

    public function report(CashSession $session)
    {
        $session->load(['cashRegister.entity', 'user', 'transactions.user', 'transactions.validateur']);
        
        $encaissements = $session->transactions()->where('type', 'encaissement')->get();
        $decaissements = $session->transactions()->where('type', 'decaissement')->get();
        
        return view('cash.sessions.report', compact('session', 'encaissements', 'decaissements'));
    }
}
