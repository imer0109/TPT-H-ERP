<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Traits\ChecksSupplierPermissions;
use App\Models\Fournisseur;
use App\Models\SupplierIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierIssueController extends Controller
{
    use ChecksSupplierPermissions;

    public function index()
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_issues.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les réclamations fournisseurs.');
        }
        
        $issues = SupplierIssue::with('fournisseur')->latest()->paginate(15);
        return view('fournisseurs.issues.index', compact('issues'));
    }

    public function create()
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.supplier_issues.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer une réclamation fournisseur.');
        }
        
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->pluck('raison_sociale','id');
        return view('fournisseurs.issues.create', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.supplier_issues.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer une réclamation fournisseur.');
        }
        
        $validated = $request->validate([
            'fournisseur_id' => ['required','exists:fournisseurs,id'],
            'type' => ['required','in:retard,non_conformite,facturation,autre'],
            'titre' => ['required','string','max:255'],
            'description' => ['nullable','string'],
        ]);

        SupplierIssue::create($validated + [
            'statut' => 'nouvelle',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('fournisseurs.issues.index')->with('success','Réclamation créée');
    }
}