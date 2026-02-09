<?php

namespace App\Http\Controllers;

use App\Models\SupplierOrder;
use App\Models\SupplierOrderItem;
use App\Models\SupplierDelivery;
use App\Models\SupplierPayment;
use App\Models\PurchaseRequest;
use App\Models\Fournisseur;
use App\Models\Agency;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierOrderController extends Controller
{
    public function index()
    {
        $orders = SupplierOrder::with(['fournisseur', 'agency', 'createdBy', 'purchaseRequest', 'items'])
            ->when(request('search'), function ($query, $search) {
                $query->where('code', 'like', "%{$search}%")
                      ->orWhereHas('fournisseur', function($q) use ($search) {
                          $q->where('nom', 'like', "%{$search}%");
                      });
            })
            ->when(request('statut'), function ($query, $statut) {
                $query->where('statut', $statut);
            })
            ->when(request('fournisseur_id'), function ($query, $fournisseurId) {
                $query->where('fournisseur_id', $fournisseurId);
            })
            ->when(request('agency_id'), function ($query, $agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $fournisseurs = Fournisseur::all();
        $agencies = Agency::all();
        $statuts = ['Brouillon', 'En attente', 'Envoyé', 'Confirmé', 'Livré', 'Clôturé', 'Annulé'];

        return view('purchases.orders.index', compact('orders', 'fournisseurs', 'agencies', 'statuts'));
    }

    public function create()
    {
        $purchaseRequestId = request('purchase_request_id');
        $purchaseRequest = null;
        
        if ($purchaseRequestId) {
            $purchaseRequest = PurchaseRequest::with('items.product')->findOrFail($purchaseRequestId);
        }

        $fournisseurs = Fournisseur::all();
        $agencies = Agency::all();
        $products = Product::all();
        $warehouses = Warehouse::all();

        return view('purchases.orders.create', compact('purchaseRequest', 'fournisseurs', 'agencies', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'agency_id' => 'required|exists:agencies,id',
            'nature_achat' => 'required|in:Bien,Service',
            'adresse_livraison' => 'nullable|string',
            'delai_contractuel' => 'nullable|date',
            'conditions_paiement' => 'nullable|string',
            'tva_percentage' => 'nullable|numeric|min:0|max:100',
            'devise' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.designation' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.unite' => 'required|string|max:50',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
            'items.*.tva_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Calculer les montants
            $montant_ht = 0;
            $montant_tva = 0;
            
            foreach ($request->items as $item) {
                $item_ht = $item['quantite'] * $item['prix_unitaire'];
                $item_tva_rate = $item['tva_rate'] ?? ($validated['tva_percentage'] ?? 18.00);
                $item_tva = $item_ht * ($item_tva_rate / 100);
                
                $montant_ht += $item_ht;
                $montant_tva += $item_tva;
            }
            
            $montant_ttc = $montant_ht + $montant_tva;
            $tva_percentage = $validated['tva_percentage'] ?? 18.00;

            // Générer le code BOC
            $code = $this->generateOrderCode();

            $order = SupplierOrder::create([
                'purchase_request_id' => $validated['purchase_request_id'],
                'fournisseur_id' => $validated['fournisseur_id'],
                'agency_id' => $validated['agency_id'],
                'code' => $code,
                'date_commande' => now(),
                'statut' => $request->has('send_order') ? 'Envoyé' : 'Brouillon',
                'nature_achat' => $validated['nature_achat'],
                'adresse_livraison' => $validated['adresse_livraison'],
                'delai_contractuel' => $validated['delai_contractuel'],
                'conditions_paiement' => $validated['conditions_paiement'],
                'montant_ht' => $montant_ht,
                'montant_tva' => $montant_tva,
                'montant_ttc' => $montant_ttc,
                'tva_percentage' => $tva_percentage,
                'devise' => $validated['devise'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id()
            ]);

            // Créer les items
            foreach ($request->items as $itemData) {
                $item_ht = $itemData['quantite'] * $itemData['prix_unitaire'];
                $item_tva_rate = $itemData['tva_rate'] ?? $tva_percentage;
                $item_tva = $item_ht * ($item_tva_rate / 100);
                
                SupplierOrderItem::create([
                    'supplier_order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'designation' => $itemData['designation'],
                    'description' => $itemData['description'] ?? null,
                    'quantite' => $itemData['quantite'],
                    'unite' => $itemData['unite'],
                    'prix_unitaire' => $itemData['prix_unitaire'],
                    'montant_total' => $item_ht,
                    'tva_rate' => $item_tva_rate,
                    'tva_amount' => $item_tva
                ]);
            }

            // Si créé depuis une demande d'achat, mettre à jour le statut
            if ($validated['purchase_request_id']) {
                PurchaseRequest::find($validated['purchase_request_id'])->update([
                    'statut' => 'Convertie en BOC'
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.orders.show', $order)
                ->with('success', 'Bon de commande créé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du bon de commande: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(SupplierOrder $order)
    {
        $order->load([
            'fournisseur', 
            'agency', 
            'createdBy', 
            'purchaseRequest',
            'items.product',
            'deliveries.items',
            'payments'
        ]);

        return view('purchases.orders.show', compact('order'));
    }

    public function edit(SupplierOrder $order)
    {
        if (!in_array($order->statut, ['Brouillon', 'En attente'])) {
            return redirect()->route('purchases.orders.show', $order)
                ->with('error', 'Ce bon de commande ne peut plus être modifié.');
        }

        $fournisseurs = Fournisseur::all();
        $agencies = Agency::all();
        $products = Product::all();
        $warehouses = Warehouse::all();
        $order->load('items');

        return view('purchases.orders.edit', compact('order', 'fournisseurs', 'agencies', 'products', 'warehouses'));
    }

    public function update(Request $request, SupplierOrder $order)
    {
        if (!in_array($order->statut, ['Brouillon', 'En attente'])) {
            return redirect()->route('purchases.orders.show', $order)
                ->with('error', 'Ce bon de commande ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'agency_id' => 'required|exists:agencies,id',
            'nature_achat' => 'required|in:Bien,Service',
            'adresse_livraison' => 'nullable|string',
            'delai_contractuel' => 'nullable|date',
            'conditions_paiement' => 'nullable|string',
            'tva_percentage' => 'nullable|numeric|min:0|max:100',
            'devise' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.designation' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.unite' => 'required|string|max:50',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
            'items.*.tva_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Calculer les montants
            $montant_ht = 0;
            $montant_tva = 0;
            
            foreach ($request->items as $item) {
                $item_ht = $item['quantite'] * $item['prix_unitaire'];
                $item_tva_rate = $item['tva_rate'] ?? ($validated['tva_percentage'] ?? 18.00);
                $item_tva = $item_ht * ($item_tva_rate / 100);
                
                $montant_ht += $item_ht;
                $montant_tva += $item_tva;
            }
            
            $montant_ttc = $montant_ht + $montant_tva;
            $tva_percentage = $validated['tva_percentage'] ?? 18.00;

            $order->update([
                'fournisseur_id' => $validated['fournisseur_id'],
                'agency_id' => $validated['agency_id'],
                'nature_achat' => $validated['nature_achat'],
                'adresse_livraison' => $validated['adresse_livraison'],
                'delai_contractuel' => $validated['delai_contractuel'],
                'conditions_paiement' => $validated['conditions_paiement'],
                'montant_ht' => $montant_ht,
                'montant_tva' => $montant_tva,
                'montant_ttc' => $montant_ttc,
                'tva_percentage' => $tva_percentage,
                'devise' => $validated['devise'],
                'notes' => $validated['notes'],
                'statut' => $request->has('send_order') ? 'Envoyé' : $order->statut
            ]);

            // Supprimer les anciens items et en créer de nouveaux
            $order->items()->delete();
            foreach ($request->items as $itemData) {
                $item_ht = $itemData['quantite'] * $itemData['prix_unitaire'];
                $item_tva_rate = $itemData['tva_rate'] ?? $tva_percentage;
                $item_tva = $item_ht * ($item_tva_rate / 100);
                
                SupplierOrderItem::create([
                    'supplier_order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'designation' => $itemData['designation'],
                    'description' => $itemData['description'] ?? null,
                    'quantite' => $itemData['quantite'],
                    'unite' => $itemData['unite'],
                    'prix_unitaire' => $itemData['prix_unitaire'],
                    'montant_total' => $item_ht,
                    'tva_rate' => $item_tva_rate,
                    'tva_amount' => $item_tva
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.orders.show', $order)
                ->with('success', 'Bon de commande mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(SupplierOrder $order)
    {
        if (!in_array($order->statut, ['Brouillon', 'Annulé'])) {
            return redirect()->route('purchases.orders.index')
                ->with('error', 'Ce bon de commande ne peut pas être supprimé.');
        }

        $order->delete();

        return redirect()->route('purchases.orders.index')
            ->with('success', 'Bon de commande supprimé avec succès.');
    }

    public function generatePdf(SupplierOrder $order)
    {
        $order->load(['fournisseur', 'agency', 'createdBy', 'items.product', 'company']);

        $pdf = Pdf::loadView('purchases.orders.pdf', compact('order'));
        
        return $pdf->download("BOC-{$order->code}.pdf");
    }

    public function updateStatus(Request $request, SupplierOrder $order)
    {
        $validated = $request->validate([
            'statut' => 'required|in:Brouillon,En attente,Envoyé,Confirmé,Livré,Clôturé,Annulé',
            'notes' => 'nullable|string'
        ]);

        $order->update([
            'statut' => $validated['statut'],
            'notes' => $validated['notes'] ?? $order->notes
        ]);

        return redirect()->route('purchases.orders.show', $order)
            ->with('success', 'Statut mis à jour avec succès.');
    }

    public function createDelivery(SupplierOrder $order)
    {
        // Check if order is confirmed or delivered
        if (!in_array($order->statut, ['Confirmé', 'Livré', 'Partiellement livré'])) {
            return redirect()->route('purchases.orders.show', $order)
                ->with('error', 'Le bon de commande doit être confirmé pour créer une livraison.');
        }

        $warehouses = Warehouse::all();
        
        return view('purchases.deliveries.create', compact('order', 'warehouses'));
    }

    private function generateOrderCode()
    {
        $year = date('Y');
        $month = date('m');
        $sequence = SupplierOrder::whereYear('created_at', $year)->count() + 1;
        
        return sprintf('BOC-%s-%s-%04d', $year, $month, $sequence);
    }

    public function dashboard()
    {
        // Statistiques de base
        $stats = [
            'total_requests' => PurchaseRequest::count(),
            'pending_requests' => PurchaseRequest::where('statut', 'En attente')->count(),
            'total_orders' => SupplierOrder::count(),
            'pending_orders' => SupplierOrder::where('statut', 'En attente')->count(),
            'confirmed_orders' => SupplierOrder::where('statut', 'Confirmé')->count(),
            'delivered_orders' => SupplierOrder::where('statut', 'Livré')->count(),
            'total_amount' => SupplierOrder::sum('montant_ttc'),
            'monthly_amount' => SupplierOrder::whereMonth('created_at', date('m'))
                                              ->whereYear('created_at', date('Y'))
                                              ->sum('montant_ttc'),
        ];

        // Commandes récentes
        $recent_orders = SupplierOrder::with(['fournisseur', 'agency'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top fournisseurs
        $top_suppliers = SupplierOrder::select('fournisseur_id', 
                                              DB::raw('COUNT(*) as order_count'), 
                                              DB::raw('SUM(montant_ttc) as total_amount'))
            ->with('fournisseur')
            ->groupBy('fournisseur_id')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // Alertes
        $alerts = [
            'pending_requests' => PurchaseRequest::where('statut', 'En attente')->count(),
            'overdue_orders' => SupplierOrder::where('delai_contractuel', '<', now())
                                             ->whereNotIn('statut', ['Livré', 'Clôturé', 'Annulé'])
                                             ->count(),
        ];

        // Données pour les graphiques
        $chartData = [
            'monthly' => [
                'labels' => collect(range(1, 12))->map(function($month) {
                    return \Carbon\Carbon::create(null, $month, 1)->format('M');
                })->toArray(),
                'data' => collect(range(1, 12))->map(function($month) {
                    return SupplierOrder::whereMonth('created_at', $month)
                                       ->whereYear('created_at', date('Y'))
                                       ->sum('montant_ttc');
                })->toArray()
            ],
            'nature' => [
                PurchaseRequest::where('nature_achat', 'Bien')->count(),
                PurchaseRequest::where('nature_achat', 'Service')->count()
            ]
        ];

        // Budget (exemple)
        $budget = [
            'monthly_limit' => 10000000, // 10M FCFA
        ];

        return view('purchases.dashboard', compact('stats', 'recent_orders', 'top_suppliers', 'alerts', 'chartData', 'budget'));
    }

    public function analytics()
    {
        // Page d'analyses détaillées
        return view('purchases.analytics');
    }
}
