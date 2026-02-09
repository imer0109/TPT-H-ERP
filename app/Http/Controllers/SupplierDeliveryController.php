<?php

namespace App\Http\Controllers;

use App\Models\SupplierDelivery;
use App\Models\SupplierDeliveryItem;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderItem;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = SupplierDelivery::with(['order', 'fournisseur', 'warehouse', 'receivedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('purchases.deliveries.index', compact('deliveries'));
    }

    public function create(SupplierOrder $order)
    {
        $warehouses = Warehouse::all();
        
        // Load order items for reference
        $order->load('items.product');
        
        return view('purchases.deliveries.create', compact('order', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_order_id' => 'required|exists:supplier_orders,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'numero_bl' => 'required|string|max:255',
            'date_reception' => 'required|date',
            'condition_emballage' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|exists:supplier_order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantite_commandee' => 'required|integer|min:0',
            'items.*.quantite_livree' => 'required|integer|min:0',
            'items.*.ecart' => 'nullable|integer',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create the delivery
            $delivery = SupplierDelivery::create([
                'supplier_order_id' => $validated['supplier_order_id'],
                'fournisseur_id' => $request->order->fournisseur_id,
                'warehouse_id' => $validated['warehouse_id'],
                'numero_bl' => $validated['numero_bl'],
                'date_reception' => $validated['date_reception'],
                'condition_emballage' => $validated['condition_emballage'],
                'notes' => $validated['notes'],
                'statut' => 'received',
                'received_by' => Auth::id(),
            ]);

            // Create delivery items and check for discrepancies
            $hasDiscrepancies = false;
            foreach ($validated['items'] as $itemData) {
                $ecart = $itemData['quantite_livree'] - $itemData['quantite_commandee'];
                
                if ($ecart != 0) {
                    $hasDiscrepancies = true;
                }
                
                $delivery->items()->create([
                    'supplier_order_item_id' => $itemData['order_item_id'],
                    'product_id' => $itemData['product_id'],
                    'quantite_commandee' => $itemData['quantite_commandee'],
                    'quantite_livree' => $itemData['quantite_livree'],
                    'ecart' => $ecart,
                    'notes' => $itemData['notes'],
                ]);
            }

            // Update delivery status based on discrepancies
            if ($hasDiscrepancies) {
                $delivery->update(['statut' => 'partial']);
            }

            // Update order status to 'Livré' if all items are delivered
            $this->updateOrderStatus($delivery->order);

            DB::commit();

            return redirect()->route('purchases.deliveries.show', $delivery)
                ->with('success', 'Livraison enregistrée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de la livraison: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(SupplierDelivery $delivery)
    {
        $delivery->load(['order', 'fournisseur', 'warehouse', 'receivedBy', 'items.product']);

        return view('purchases.deliveries.show', compact('delivery'));
    }

    public function edit(SupplierDelivery $delivery)
    {
        $warehouses = Warehouse::all();
        $delivery->load('items.product');

        return view('purchases.deliveries.edit', compact('delivery', 'warehouses'));
    }

    public function update(Request $request, SupplierDelivery $delivery)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'numero_bl' => 'required|string|max:255',
            'date_reception' => 'required|date',
            'condition_emballage' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:supplier_delivery_items,id',
            'items.*.quantite_livree' => 'required|integer|min:0',
            'items.*.ecart' => 'nullable|integer',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update the delivery
            $delivery->update([
                'warehouse_id' => $validated['warehouse_id'],
                'numero_bl' => $validated['numero_bl'],
                'date_reception' => $validated['date_reception'],
                'condition_emballage' => $validated['condition_emballage'],
                'notes' => $validated['notes'],
            ]);

            // Update delivery items
            $hasDiscrepancies = false;
            foreach ($validated['items'] as $itemData) {
                $item = SupplierDeliveryItem::find($itemData['id']);
                if ($item) {
                    $ecart = $itemData['quantite_livree'] - $item->quantite_commandee;
                    
                    if ($ecart != 0) {
                        $hasDiscrepancies = true;
                    }
                    
                    $item->update([
                        'quantite_livree' => $itemData['quantite_livree'],
                        'ecart' => $ecart,
                        'notes' => $itemData['notes'],
                    ]);
                }
            }

            // Update delivery status based on discrepancies
            $newStatus = $hasDiscrepancies ? 'partial' : 'received';
            $delivery->update(['statut' => $newStatus]);

            // Update order status
            $this->updateOrderStatus($delivery->order);

            DB::commit();

            return redirect()->route('purchases.deliveries.show', $delivery)
                ->with('success', 'Livraison mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de la livraison: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(SupplierDelivery $delivery)
    {
        DB::beginTransaction();

        try {
            // Delete delivery items
            $delivery->items()->delete();
            
            // Delete delivery
            $delivery->delete();

            // Update order status
            $this->updateOrderStatus($delivery->order);

            DB::commit();

            return redirect()->route('purchases.deliveries.index')
                ->with('success', 'Livraison supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de la livraison: ' . $e->getMessage()]);
        }
    }

    public function validateDelivery(Request $request, SupplierDelivery $delivery)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            if ($validated['action'] === 'approve') {
                $delivery->update([
                    'statut' => 'validated',
                    'validated_by' => Auth::id(),
                    'validated_at' => now(),
                    'validation_notes' => $validated['notes'],
                ]);

                // Update order status
                $this->updateOrderStatus($delivery->order);

                $message = 'Livraison validée avec succès.';
            } else {
                $delivery->update([
                    'statut' => 'rejected',
                    'validated_by' => Auth::id(),
                    'validated_at' => now(),
                    'validation_notes' => $validated['notes'],
                ]);

                $message = 'Livraison rejetée.';
            }

            DB::commit();

            return redirect()->route('purchases.deliveries.show', $delivery)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la validation de la livraison: ' . $e->getMessage()]);
        }
    }

    public function receiveService(Request $request, SupplierOrder $order)
    {
        $validated = $request->validate([
            'date_realisation' => 'required|date',
            'compte_rendu' => 'required|string',
            'preuve_service' => 'nullable|string',
            'notes' => 'nullable|string',
            'satisfaction' => 'nullable|integer|min:1|max:5',
        ]);

        DB::beginTransaction();

        try {
            // Create a delivery record for service
            $delivery = SupplierDelivery::create([
                'supplier_order_id' => $order->id,
                'fournisseur_id' => $order->fournisseur_id,
                'warehouse_id' => null, // Services don't have warehouses
                'numero_bl' => 'SERVICE-' . $order->code,
                'date_reception' => $validated['date_realisation'],
                'notes' => $validated['notes'],
                'statut' => 'service_delivered',
                'received_by' => Auth::id(),
                'is_service' => true,
            ]);

            // Create a delivery item for the service
            $delivery->items()->create([
                'supplier_order_item_id' => $order->items->first()?->id,
                'product_id' => null,
                'quantite_commandee' => 1,
                'quantite_livree' => 1,
                'ecart' => 0,
                'compte_rendu' => $validated['compte_rendu'],
                'preuve_service' => $validated['preuve_service'],
                'satisfaction' => $validated['satisfaction'],
            ]);

            // Update order status
            $order->update(['statut' => 'Livré']);

            DB::commit();

            return redirect()->route('purchases.deliveries.show', $delivery)
                ->with('success', 'Service livré avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de la livraison du service: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update the order status based on deliveries
     */
    private function updateOrderStatus(SupplierOrder $order)
    {
        // Get all deliveries for this order
        $deliveries = $order->deliveries;
        
        if ($deliveries->isEmpty()) {
            return;
        }
        
        // Check if all items are delivered
        $totalOrdered = $order->items->sum('quantite');
        $totalDelivered = $deliveries->sum(function ($delivery) {
            return $delivery->items->sum('quantite_livree');
        });
        
        if ($totalDelivered >= $totalOrdered) {
            $order->update(['statut' => 'Livré']);
        } elseif ($totalDelivered > 0) {
            $order->update(['statut' => 'Partiellement livré']);
        }
    }
}