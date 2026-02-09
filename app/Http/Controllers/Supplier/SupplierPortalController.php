<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use App\Models\SupplierOrder;
use App\Models\SupplierDelivery;
use App\Models\SupplierPayment;
use App\Models\SupplierInvoice;
use App\Models\SupplierIssue;
use App\Models\SupplierContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierPortalController extends Controller
{
    /**
     * Display the supplier portal dashboard.
     */
    public function index()
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        // Get the authenticated supplier
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        // Load supplier with relationships if supplier exists
        if ($supplier) {
            $supplier->load(['societe', 'agency']);
        }
        
        // Get recent orders
        $recentOrders = $supplier ? $supplier->supplierOrders()
            ->with(['agency', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get() : collect();
        
        // Get recent deliveries
        $recentDeliveries = $supplier ? $supplier->supplierDeliveries()
            ->with(['order', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get() : collect();
        
        // Get recent invoices
        $recentInvoices = $supplier ? $supplier->supplierInvoices()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get() : collect();
        
        // Get open issues
        $openIssues = $supplier ? $supplier->supplierIssues()
            ->where('statut', 'open')
            ->orderBy('created_at', 'desc')
            ->get() : collect();
        
        // Get active contracts
        $activeContracts = $supplier ? $supplier->supplierContracts()
            ->where('status', 'active')
            ->orderBy('end_date', 'asc')
            ->get() : collect();
        
        // Calculate financial metrics
        $totalOrdersAmount = $supplier ? $supplier->supplierOrders()->sum('montant_ttc') : 0;
        $totalPaymentsAmount = $supplier ? $supplier->supplierPayments()->sum('montant') : 0;
        $outstandingBalance = $totalOrdersAmount - $totalPaymentsAmount;
        
        return view('fournisseurs.portal.index', compact(
            'supplier',
            'recentOrders',
            'recentDeliveries',
            'recentInvoices',
            'openIssues',
            'activeContracts',
            'totalOrdersAmount',
            'totalPaymentsAmount',
            'outstandingBalance'
        ));
    }
    
    /**
     * Display supplier profile.
     */
    public function profile()
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        // Load supplier with relationships if supplier exists
        if ($supplier) {
            $supplier->load(['societe', 'agency']);
        }
        
        return view('fournisseurs.portal.profile', compact('supplier'));
    }
    
    /**
     * Update supplier profile.
     */
    public function updateProfile(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.edit')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.edit')) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'raison_sociale' => 'required|string|max:255',
            'type' => 'required|string|in:personne_physique,entreprise,institution',
            'activite' => 'required|string|in:transport,logistique,matieres_premieres,services,autre',
            'niu' => 'nullable|string|max:50',
            'rccm' => 'nullable|string|max:50',
            'cnss' => 'nullable|string|max:50',
            'adresse' => 'required|string',
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'contact_principal' => 'required|string|max:255',
            'banque' => 'nullable|string|max:255',
            'numero_compte' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'devise' => 'nullable|string|max:10',
        ]);
        
        $supplier->update($validated);
        
        return redirect()->route('supplier.portal.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
    
    /**
     * Display supplier orders.
     */
    public function orders(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = $supplier ? $supplier->supplierOrders() : SupplierOrder::query();
        $query = $query->with(['agency', 'createdBy']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('statut', $request->input('status'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('date_commande', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date_commande', '<=', $request->input('date_to'));
        }
        
        $orders = $query->orderBy('date_commande', 'desc')->paginate(15);
        
        return view('fournisseurs.portal.orders', compact('orders'));
    }
    
    /**
     * Display supplier deliveries.
     */
    public function deliveries(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_deliveries.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_deliveries.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = $supplier ? $supplier->supplierDeliveries() : SupplierDelivery::query();
        $query = $query->with(['order', 'warehouse', 'receivedBy']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('statut', $request->input('status'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('date_reception', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date_reception', '<=', $request->input('date_to'));
        }
        
        $deliveries = $query->orderBy('date_reception', 'desc')->paginate(15);
        
        return view('fournisseurs.portal.deliveries', compact('deliveries'));
    }
    
    /**
     * Display supplier invoices.
     */
    public function invoices(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_invoices.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_invoices.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = $supplier ? $supplier->supplierInvoices() : SupplierInvoice::query();
        $query = $query->with(['order']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('statut', $request->input('status'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('date_facture', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date_facture', '<=', $request->input('date_to'));
        }
        
        $invoices = $query->orderBy('date_facture', 'desc')->paginate(15);
        
        return view('fournisseurs.portal.invoices', compact('invoices'));
    }
    
    /**
     * Display supplier payments.
     */
    public function payments(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_payments.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_payments.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = $supplier ? $supplier->supplierPayments() : SupplierPayment::query();
        $query = $query->with(['invoice', 'validatedBy']);
        
        // Apply filters
        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->input('mode_paiement'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('date_paiement', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date_paiement', '<=', $request->input('date_to'));
        }
        
        $payments = $query->orderBy('date_paiement', 'desc')->paginate(15);
        
        return view('fournisseurs.portal.payments', compact('payments'));
    }
    
    /**
     * Display supplier contracts.
     */
    public function contracts()
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_contracts.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_contracts.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $contracts = $supplier ? $supplier->supplierContracts()->with(['responsible'])->orderBy('end_date', 'desc')->paginate(15) : SupplierContract::query()->with(['responsible'])->orderBy('end_date', 'desc')->paginate(15);
        
        return view('fournisseurs.portal.contracts', compact('contracts'));
    }
    
    /**
     * Display supplier issues.
     */
    public function issues(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = $supplier ? $supplier->supplierIssues() : SupplierIssue::query();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('statut', $request->input('status'));
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        
        $issues = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('fournisseurs.portal.issues', compact('issues'));
    }
    
    /**
     * Create a new issue.
     */
    public function createIssue()
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.create')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.create')) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('fournisseurs.portal.create-issue');
    }
    
    /**
     * Store a new issue.
     */
    public function storeIssue(Request $request)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.create')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.create')) {
            abort(403, 'Accès non autorisé');
        }
        
        // If user is not a supplier but has management permissions, they should not be able to create issues
        if (!$supplier) {
            abort(403, 'Seuls les fournisseurs peuvent créer des réclamations');
        }
        
        $validated = $request->validate([
            'type' => 'required|string|in:retard,produit_non_conforme,erreur_facturation,autre',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $validated['fournisseur_id'] = $supplier->id;
        $validated['statut'] = 'open';
        $validated['created_by'] = Auth::id();
        
        $issue = SupplierIssue::create($validated);
        
        return redirect()->route('supplier.portal.issues')
            ->with('success', 'Réclamation créée avec succès.');
    }
        
    /**
     * Show specific order details.
     */
    public function showOrder($orderId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $supplier = Auth::user()->fournisseur;
            
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $order = $supplier ? $supplier->supplierOrders()->findOrFail($orderId) : \App\Models\SupplierOrder::findOrFail($orderId);
            
        return view('fournisseurs.portal.orders.show', compact('order'));
    }
        
    /**
     * Show specific delivery details.
     */
    public function showDelivery($deliveryId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_deliveries.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $supplier = Auth::user()->fournisseur;
            
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_deliveries.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $delivery = $supplier ? $supplier->supplierDeliveries()->findOrFail($deliveryId) : \App\Models\SupplierDelivery::findOrFail($deliveryId);
            
        return view('fournisseurs.portal.deliveries.show', compact('delivery'));
    }
        
    /**
     * Show specific invoice details.
     */
    public function showInvoice($invoiceId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_invoices.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $supplier = Auth::user()->fournisseur;
            
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_invoices.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $invoice = $supplier ? $supplier->supplierInvoices()->findOrFail($invoiceId) : \App\Models\SupplierInvoice::findOrFail($invoiceId);
            
        return view('fournisseurs.portal.invoices.show', compact('invoice'));
    }
        
    /**
     * Show specific payment details.
     */
    public function showPayment($paymentId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_payments.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $supplier = Auth::user()->fournisseur;
            
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_payments.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $payment = $supplier ? $supplier->supplierPayments()->findOrFail($paymentId) : \App\Models\SupplierPayment::findOrFail($paymentId);
            
        return view('fournisseurs.portal.payments.show', compact('payment'));
    }
        
    /**
     * Show specific contract details.
     */
    public function showContract($contractId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_contracts.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $supplier = Auth::user()->fournisseur;
            
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_contracts.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $contract = $supplier ? $supplier->supplierContracts()->findOrFail($contractId) : \App\Models\SupplierContract::findOrFail($contractId);
            
        return view('fournisseurs.portal.contracts.show', compact('contract'));
    }
        
    /**
     * Show specific issue details.
     */
    public function showIssue($issueId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $supplier = Auth::user()->fournisseur;
            
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.supplier_issues.view')) {
            abort(403, 'Accès non autorisé');
        }
            
        $issue = $supplier ? $supplier->supplierIssues()->findOrFail($issueId) : \App\Models\SupplierIssue::findOrFail($issueId);
            
        return view('fournisseurs.portal.issues.show', compact('issue'));
    }
        
    /**
     * Download a document.
     */
    public function downloadDocument($documentId)
    {
        // Check permission
        if (!Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $supplier = Auth::user()->fournisseur;
        
        // If user is not a supplier, allow access if they have supplier management permissions
        if (!$supplier && !Auth::user()->hasRole('administrateur') && !Auth::user()->canAccessModule('suppliers') && !Auth::user()->hasPermission('suppliers.fournisseurs.view')) {
            abort(403, 'Accès non autorisé');
        }
        
        $document = $supplier ? $supplier->documents()->findOrFail($documentId) : \App\Models\FournisseurDocument::findOrFail($documentId);
        
        // Check if file exists
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($document->chemin_fichier)) {
            abort(404, 'Document non trouvé');
        }
        
        $path = \Illuminate\Support\Facades\Storage::disk('public')->path($document->chemin_fichier);
        
        return response()->download($path, $document->nom_fichier);
    }
}