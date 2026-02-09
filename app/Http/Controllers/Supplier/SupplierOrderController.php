<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Traits\ChecksSupplierPermissions;
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;

class SupplierOrderController extends Controller
{
    use ChecksSupplierPermissions;

    public function index()
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les commandes fournisseurs.');
        }
        
        $orders = SupplierOrder::with(['fournisseur','agency'])->latest()->paginate(15);
        return view('fournisseurs.orders.index', compact('orders'));
    }

    public function create()
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_orders.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer une commande fournisseur.');
        }
        
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->pluck('raison_sociale','id');
        $products = Product::orderBy('name')->pluck('name','id');
        return view('fournisseurs.orders.create', compact('fournisseurs','products'));
    }

    public function store(Request $request)
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_orders.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer une commande fournisseur.');
        }
        
        $validated = $request->validate([
            'fournisseur_id' => ['required','exists:fournisseurs,id'],
            'date_commande' => ['required','date'],
            'devise' => ['nullable','string','max:10'],
            'notes' => ['nullable','string'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['nullable','uuid'],
            'items.*.designation' => ['required_without:items.*.product_id','string'],
            'items.*.quantite' => ['required','numeric','min:0.001'],
            'items.*.unite' => ['nullable','string','max:10'],
            'items.*.prix_unitaire' => ['required','numeric','min:0'],
        ]);

        $code = 'BOC-'.date('Ymd-His').'-'.mt_rand(100,999);

        $order = SupplierOrder::create([
            'fournisseur_id' => $validated['fournisseur_id'],
            'agency_id' => null,
            'code' => $code,
            'date_commande' => $validated['date_commande'],
            'statut' => 'commande',
            'devise' => $validated['devise'] ?? 'XOF',
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        $montantHt = 0;
        foreach ($validated['items'] as $it) {
            $ligneTotal = ($it['quantite'] * $it['prix_unitaire']);
            $montantHt += $ligneTotal;
            SupplierOrderItem::create([
                'supplier_order_id' => $order->id,
                'product_id' => $it['product_id'] ?? null,
                'designation' => $it['designation'] ?? (Product::find($it['product_id'])->name ?? ''),
                'quantite' => $it['quantite'],
                'unite' => $it['unite'] ?? 'U',
                'prix_unitaire' => $it['prix_unitaire'],
                'montant_total' => $ligneTotal,
            ]);
        }
        $order->update([
            'montant_ht' => $montantHt,
            'montant_tva' => 0,
            'montant_ttc' => $montantHt,
        ]);

        return redirect()->route('fournisseurs.orders.index')->with('success', 'Bon de commande créé');
    }

    public function show(SupplierOrder $order)
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir cette commande fournisseur.');
        }
        
        $order->load(['fournisseur','items.product','createdBy']);
        return view('fournisseurs.orders.show', compact('order'));
    }

    public function exportCsv(): StreamedResponse
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_orders.export')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour exporter les commandes fournisseurs.');
        }
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="supplier_orders.csv"',
        ];
        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Code','Fournisseur','Date','Statut','Montant TTC','Devise'], ';');
            SupplierOrder::with('fournisseur')->chunk(200, function($chunk) use ($handle) {
                foreach ($chunk as $o) {
                    fputcsv($handle, [
                        $o->code,
                        $o->fournisseur->raison_sociale ?? '-',
                        $o->date_commande,
                        $o->statut,
                        number_format($o->montant_ttc, 2, ',', ' '),
                        $o->devise,
                    ], ';');
                }
            });
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function pdf(SupplierOrder $order)
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_orders.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir cette commande fournisseur.');
        }
        
        $order->load(['fournisseur','items.product']);
        // Simple HTML-to-PDF via browser print; for real PDF, integrate barryvdh/laravel-dompdf
        return view('fournisseurs.orders.show', compact('order'));
    }
}