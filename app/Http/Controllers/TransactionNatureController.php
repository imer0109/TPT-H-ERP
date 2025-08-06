<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransactionNature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TransactionNatureController extends Controller
{
     public function index()
    {
        $natures = TransactionNature::with('creator')->paginate(10);
        return view('cash.natures.index', compact('natures'));
    }

    public function create()
    {
        return view('cash.natures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:transaction_natures',
            'description' => 'nullable|string'
        ]);

        TransactionNature::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'actif' => true,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('cash.natures.index')
            ->with('success', 'Nature de transaction créée avec succès.');
    }

    public function edit(TransactionNature $nature)
    {
        return view('cash.natures.edit', compact('nature'));
    }

    public function update(Request $request, TransactionNature $nature)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:transaction_natures,nom,' . $nature->id,
            'description' => 'nullable|string',
            'actif' => 'boolean'
        ]);

        $nature->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? $nature->description,
            'actif' => $request->has('actif')
        ]);

        return redirect()->route('cash.natures.index')
            ->with('success', 'Nature de transaction mise à jour avec succès.');
    }

    public function destroy(TransactionNature $nature)
    {
        // Vérifier si la nature est utilisée dans des transactions
        $isUsed = CashTransaction::where('nature_operation', $nature->nom)->exists();
        
        if ($isUsed) {
            return redirect()->route('cash.natures.index')
                ->with('error', 'Cette nature de transaction est utilisée et ne peut pas être supprimée.');
        }
        
        $nature->delete();
        
        return redirect()->route('cash.natures.index')
            ->with('success', 'Nature de transaction supprimée avec succès.');
    }
}
