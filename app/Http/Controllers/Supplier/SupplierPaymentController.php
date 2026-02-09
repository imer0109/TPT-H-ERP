<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Traits\ChecksSupplierPermissions;
use App\Models\Fournisseur;
use App\Models\SupplierPayment;
use App\Models\SupplierInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SupplierPaymentController extends Controller
{
    use ChecksSupplierPermissions;

    public function index()
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_payments.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les paiements fournisseurs.');
        }
        
        $payments = SupplierPayment::with(['fournisseur', 'invoice'])->latest()->paginate(15);
        $overdueInvoices = SupplierInvoice::where('date_echeance', '<', now())
            ->whereRaw('montant_total > montant_paye')
            ->with('fournisseur')
            ->get();
        
        return view('fournisseurs.payments.index', compact('payments', 'overdueInvoices'));
    }

    public function create()
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_payments.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer un paiement fournisseur.');
        }
        
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->pluck('raison_sociale','id');
        $invoices = SupplierInvoice::whereRaw('montant_total > montant_paye')
            ->with('fournisseur')
            ->get()
            ->mapWithKeys(function($invoice) {
                return [$invoice->id => $invoice->numero_facture . ' - ' . $invoice->fournisseur->raison_sociale . ' (' . number_format($invoice->solde, 0, ',', ' ') . ' XAF)'];
            });
        
        return view('fournisseurs.payments.create', compact('fournisseurs', 'invoices'));
    }

    public function store(Request $request)
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_payments.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer un paiement fournisseur.');
        }
        
        $validated = $request->validate([
            'fournisseur_id' => ['required','exists:fournisseurs,id'],
            'supplier_invoice_id' => ['nullable','exists:supplier_invoices,id'],
            'date_paiement' => ['required','date'],
            'mode_paiement' => ['required','in:virement,cheque,especes,carte,autre'],
            'montant' => ['required','numeric','min:0.01'],
            'devise' => ['nullable','string','max:3'],
            'reference_paiement' => ['nullable','string','max:255'],
            'justificatif' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:2048'],
            'notes' => ['nullable','string'],
        ]);

        // Gestion du fichier justificatif
        if ($request->hasFile('justificatif')) {
            $validated['justificatif'] = $request->file('justificatif')->store('supplier-payments', 'public');
        }

        $payment = SupplierPayment::create($validated + [
            'created_by' => auth()->id(),
            'validated_by' => null,
        ]);

        // Mettre à jour le montant payé de la facture si applicable
        if ($validated['supplier_invoice_id']) {
            $invoice = SupplierInvoice::find($validated['supplier_invoice_id']);
            $invoice->montant_paye += $validated['montant'];
            
            // Mettre à jour le statut
            if ($invoice->montant_paye >= $invoice->montant_total) {
                $invoice->statut = 'paid';
            } else {
                $invoice->statut = 'partially_paid';
            }
            
            $invoice->save();
        }

        return redirect()->route('fournisseurs.payments.index')->with('success','Paiement enregistré');
    }
    
    public function validatePayment(SupplierPayment $payment)
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_payments.edit')) {
            if (!Auth::user()->can('validate', $payment)) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider ce paiement.');
            }
        }
            
        $payment->update([
            'validated_by' => Auth::id(),
            'validated_at' => now(),
            'status' => 'validated'
        ]);
        
        return redirect()->back()->with('success', 'Paiement validé avec succès.');
    }
}