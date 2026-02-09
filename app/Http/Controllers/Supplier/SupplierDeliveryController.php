<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Traits\ChecksSupplierPermissions;
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\SupplierDelivery;
use App\Models\SupplierOrder;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierDeliveryController extends Controller
{
    use ChecksSupplierPermissions;

    public function index()
    {
        if (!$this->checkSupplierPermission('suppliers.supplier_deliveries.view')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour voir les livraisons fournisseurs.');
        }
        
        $deliveries = SupplierDelivery::with(['fournisseur','warehouse'])->latest()->paginate(15);
        return view('fournisseurs.deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.supplier_deliveries.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer une livraison fournisseur.');
        }
        
        $fournisseurs = Fournisseur::orderBy('raison_sociale')->pluck('raison_sociale','id');
        $warehouses = Warehouse::orderBy('nom')->pluck('nom','id');
        $orders = SupplierOrder::with('fournisseur')->where('statut', '!=', 'cancelled')->get();
        $products = Product::orderBy('name')->pluck('name','id');
        
        return view('fournisseurs.deliveries.create', compact('fournisseurs','warehouses','orders','products'));
    }

    public function store(Request $request)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.supplier_deliveries.create')) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires pour créer une livraison fournisseur.');
        }
        
        $validated = $request->validate([
            'supplier_order_id' => ['nullable','exists:supplier_orders,id'],
            'fournisseur_id' => ['required','exists:fournisseurs,id'],
            'warehouse_id' => ['required','exists:warehouses,id'],
            'numero_bl' => ['required','string','max:255'],
            'date_reception' => ['required','date'],
            'statut' => ['required','in:received,partial,returned'],
            'notes' => ['nullable','string'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','exists:products,id'],
            'items.*.quantite_livree' => ['required','integer','min:1'],
            'items.*.quantite_commandee' => ['nullable','integer','min:0'],
        ]);

        $delivery = SupplierDelivery::create([
            'supplier_order_id' => $validated['supplier_order_id'],
            'fournisseur_id' => $validated['fournisseur_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'numero_bl' => $validated['numero_bl'],
            'date_reception' => $validated['date_reception'],
            'statut' => $validated['statut'],
            'notes' => $validated['notes'],
            'received_by' => auth()->id(),
        ]);

        // Enregistrer les articles livrés
        foreach ($validated['items'] as $item) {
            $delivery->items()->create([
                'product_id' => $item['product_id'],
                'quantite_livree' => $item['quantite_livree'],
                'quantite_commandee' => $item['quantite_commandee'] ?? 0,
            ]);
        }

        return redirect()->route('fournisseurs.deliveries.index')->with('success','Livraison enregistrée');
    }
    
    public function validateDelivery(SupplierDelivery $delivery)
    {
        // Check permission
        if (!$this->checkSupplierPermission('suppliers.supplier_deliveries.edit')) {
            if (!Auth::user()->can('validate', $delivery)) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider cette livraison.');
            }
        }
            
        $delivery->update([
            'validated_by' => Auth::id(),
            'validated_at' => now(),
            'status' => 'validated'
        ]);
        
        return redirect()->back()->with('success', 'Livraison validée avec succès.');
    }
}