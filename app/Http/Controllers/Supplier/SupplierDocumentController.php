<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use App\Models\FournisseurDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierDocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index(Fournisseur $fournisseur)
    {
        $this->authorize('view', $fournisseur);
        
        $documents = $fournisseur->documents()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('fournisseurs.documents.index', compact('fournisseur', 'documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Fournisseur $fournisseur)
    {
        $this->authorize('update', $fournisseur);
        
        $types = FournisseurDocument::$types;
        
        return view('fournisseurs.documents.create', compact('fournisseur', 'types'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request, Fournisseur $fournisseur)
    {
        $this->authorize('update', $fournisseur);
        
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(FournisseurDocument::$types))],
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_expiration' => 'nullable|date|after:today',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240', // 10MB max
        ]);
        
        // Handle file upload
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('fournisseurs/' . $fournisseur->id . '/documents', $filename, 'public');
            
            $validated['chemin'] = $path;
            $validated['taille'] = $file->getSize();
            $validated['extension'] = $file->getClientOriginalExtension();
        }
        
        $validated['fournisseur_id'] = $fournisseur->id;
        $validated['uploaded_by'] = Auth::id();
        
        $document = FournisseurDocument::create($validated);
        
        return redirect()->route('fournisseurs.documents.index', $fournisseur)
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Display the specified document.
     */
    public function show(Fournisseur $fournisseur, FournisseurDocument $document)
    {
        $this->authorize('view', $fournisseur);
        
        if ($document->fournisseur_id !== $fournisseur->id) {
            abort(404);
        }
        
        return view('fournisseurs.documents.show', compact('fournisseur', 'document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Fournisseur $fournisseur, FournisseurDocument $document)
    {
        $this->authorize('update', $fournisseur);
        
        if ($document->fournisseur_id !== $fournisseur->id) {
            abort(404);
        }
        
        $types = FournisseurDocument::$types;
        
        return view('fournisseurs.documents.edit', compact('fournisseur', 'document', 'types'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur, FournisseurDocument $document)
    {
        $this->authorize('update', $fournisseur);
        
        if ($document->fournisseur_id !== $fournisseur->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(FournisseurDocument::$types))],
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_expiration' => 'nullable|date|after:today',
        ]);
        
        // Handle file replacement if provided
        if ($request->hasFile('document')) {
            $request->validate([
                'document' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240', // 10MB max
            ]);
            
            // Delete old file
            if ($document->chemin && Storage::disk('public')->exists($document->chemin)) {
                Storage::disk('public')->delete($document->chemin);
            }
            
            // Upload new file
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('fournisseurs/' . $fournisseur->id . '/documents', $filename, 'public');
            
            $validated['chemin'] = $path;
            $validated['taille'] = $file->getSize();
            $validated['extension'] = $file->getClientOriginalExtension();
        }
        
        $document->update($validated);
        
        return redirect()->route('fournisseurs.documents.show', [$fournisseur, $document])
            ->with('success', 'Document mis à jour avec succès.');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Fournisseur $fournisseur, FournisseurDocument $document)
    {
        $this->authorize('update', $fournisseur);
        
        if ($document->fournisseur_id !== $fournisseur->id) {
            abort(404);
        }
        
        $document->delete();
        
        return redirect()->route('fournisseurs.documents.index', $fournisseur)
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Download the specified document.
     */
    public function download(Fournisseur $fournisseur, FournisseurDocument $document)
    {
        $this->authorize('view', $fournisseur);
        
        if ($document->fournisseur_id !== $fournisseur->id) {
            abort(404);
        }
        
        if (!$document->chemin || !Storage::disk('public')->exists($document->chemin)) {
            abort(404, 'Document non trouvé');
        }
        
        return Storage::disk('public')->download($document->chemin, $document->nom . '.' . $document->extension);
    }

    /**
     * View the specified document in browser.
     */
    public function view(Fournisseur $fournisseur, FournisseurDocument $document)
    {
        $this->authorize('view', $fournisseur);
        
        if ($document->fournisseur_id !== $fournisseur->id) {
            abort(404);
        }
        
        if (!$document->chemin || !Storage::disk('public')->exists($document->chemin)) {
            abort(404, 'Document non trouvé');
        }
        
        // For PDF files, we can display them directly in the browser
        if (strtolower($document->extension) === 'pdf') {
            return response()->file(Storage::disk('public')->path($document->chemin));
        }
        
        // For other file types, redirect to download
        return redirect()->route('fournisseurs.documents.download', [$fournisseur, $document]);
    }
}