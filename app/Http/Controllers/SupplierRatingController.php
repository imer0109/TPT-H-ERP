<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\SupplierRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierRatingController extends Controller
{
    /**
     * Show the form for creating a new rating for a supplier.
     */
    public function create(Fournisseur $fournisseur)
    {
        return view('fournisseurs.ratings.create', compact('fournisseur'));
    }

    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'quality_rating' => 'required|integer|min:1|max:5',
            'delivery_rating' => 'required|integer|min:1|max:5',
            'responsiveness_rating' => 'required|integer|min:1|max:5',
            'pricing_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $validated['fournisseur_id'] = $fournisseur->id;
        $validated['evaluated_by'] = Auth::id();
        $validated['evaluation_date'] = now();

        $rating = SupplierRating::create($validated);

        // Update supplier's average rating
        $this->updateSupplierRating($fournisseur);

        return redirect()->route('fournisseurs.show', $fournisseur)
            ->with('success', 'Évaluation enregistrée avec succès.');
    }

    /**
     * Show the form for editing the specified rating.
     */
    public function edit(SupplierRating $rating)
    {
        return view('fournisseurs.ratings.edit', compact('rating'));
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(Request $request, SupplierRating $rating)
    {
        $validated = $request->validate([
            'quality_rating' => 'required|integer|min:1|max:5',
            'delivery_rating' => 'required|integer|min:1|max:5',
            'responsiveness_rating' => 'required|integer|min:1|max:5',
            'pricing_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        $rating->update($validated);

        // Update supplier's average rating
        $this->updateSupplierRating($rating->fournisseur);

        return redirect()->route('fournisseurs.show', $rating->fournisseur)
            ->with('success', 'Évaluation mise à jour avec succès.');
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy(SupplierRating $rating)
    {
        $fournisseur = $rating->fournisseur;
        $rating->delete();

        // Update supplier's average rating
        $this->updateSupplierRating($fournisseur);

        return redirect()->route('fournisseurs.show', $fournisseur)
            ->with('success', 'Évaluation supprimée avec succès.');
    }

    /**
     * Automatically evaluate supplier based on performance metrics.
     */
    public function autoEvaluate(Fournisseur $fournisseur)
    {
        // Create automatic evaluation using the model method
        $rating = SupplierRating::createAutomaticEvaluation($fournisseur);

        return response()->json([
            'success' => true, 
            'message' => 'Évaluation automatique effectuée.',
            'rating' => $rating
        ]);
    }

    /**
     * Update supplier's average rating and count
     */
    private function updateSupplierRating(Fournisseur $supplier)
    {
        $supplier->note_moyenne = $supplier->supplierRatings()->avg('overall_score');
        $supplier->nombre_evaluations = $supplier->supplierRatings()->count();
        $supplier->save();
    }

    /**
     * Display supplier ratings
     */
    public function index(Fournisseur $fournisseur)
    {
        $ratings = $fournisseur->supplierRatings()
            ->with('evaluator')
            ->orderBy('evaluation_date', 'desc')
            ->paginate(15);

        return view('fournisseurs.ratings.index', compact('fournisseur', 'ratings'));
    }

    /**
     * Show supplier rating details
     */
    public function show(SupplierRating $rating)
    {
        $rating->load('fournisseur', 'evaluator');
        return view('fournisseurs.ratings.show', compact('rating'));
    }
}