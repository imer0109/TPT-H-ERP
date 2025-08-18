<?php

namespace App\Http\Controllers;

use App\Models\StockInventory;
use App\Models\StockInventoryItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\StockMovement;
use App\Http\Requests\StockInventoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StockInventory::with(['warehouse', 'createdBy', 'validatedBy']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->has('date_start') && $request->has('date_end')) {
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }
        
        $inventories = $query->latest()->paginate(15);
        $warehouses = Warehouse::where('actif', true)->pluck('nom', 'id');
        
        return view('stock.inventories.index', compact('inventories', 'warehouses'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('actif', true)->pluck('nom', 'id');
        return view('stock.inventories.create', compact('warehouses'));
    }

    public function store(StockInventoryRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $inventory = StockInventory::create([
                'warehouse_id' => $request->warehouse_id,
                'date' => $request->date,
                'notes' => $request->notes,
                'status' => 'en_cours',
                'created_by' => auth()->id(),
                'reference' => 'INV-' . date('YmdHis')
            ]);
            
            // Récupérer tous les produits actifs
            $products = Product::where('actif', true)->get();
            
            // Pour chaque produit, créer un élément d'inventaire
            foreach ($products as $product) {
                // Calculer le stock théorique pour ce produit dans ce dépôt
                $theoreticalStock = $this->calculateTheoreticalStock($product->id, $request->warehouse_id);
                
                StockInventoryItem::create([
                    'inventory_id' => $inventory->id,
                    'product_id' => $product->id,
                    'theoretical_quantity' => $theoreticalStock,
                    'actual_quantity' => null,
                    'difference' => null,
                    'notes' => null
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('success', 'Inventaire créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'inventaire: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(StockInventory $inventory)
    {
        $inventory->load(['warehouse', 'createdBy', 'validatedBy', 'items.product']);
        return view('stock.inventories.show', compact('inventory'));
    }

    public function edit(StockInventory $inventory)
    {
        if ($inventory->status !== 'en_cours') {
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('error', 'Cet inventaire ne peut plus être modifié');
        }
        
        $inventory->load(['warehouse', 'items.product']);
        return view('stock.inventories.edit', compact('inventory'));
    }

    public function update(Request $request, StockInventory $inventory)
    {
        if ($inventory->status !== 'en_cours') {
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('error', 'Cet inventaire ne peut plus être modifié');
        }
        
        DB::beginTransaction();
        
        try {
            // Mettre à jour les notes de l'inventaire
            $inventory->update([
                'notes' => $request->notes
            ]);
            
            // Mettre à jour les quantités réelles et les notes pour chaque élément
            foreach ($request->items as $itemId => $itemData) {
                $item = StockInventoryItem::findOrFail($itemId);
                
                $actualQuantity = $itemData['actual_quantity'] !== '' ? $itemData['actual_quantity'] : null;
                $difference = $actualQuantity !== null ? $actualQuantity - $item->theoretical_quantity : null;
                
                $item->update([
                    'actual_quantity' => $actualQuantity,
                    'difference' => $difference,
                    'notes' => $itemData['notes'] ?? null
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('success', 'Inventaire mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'inventaire: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function validate(StockInventory $inventory)
    {
        if ($inventory->status !== 'en_cours') {
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('error', 'Cet inventaire ne peut pas être validé');
        }
        
        // Vérifier que toutes les quantités réelles ont été saisies
        $incompleteItems = $inventory->items()->whereNull('actual_quantity')->count();
        
        if ($incompleteItems > 0) {
            return redirect()->route('stock.inventories.edit', $inventory)
                ->with('error', 'Toutes les quantités réelles doivent être saisies avant de valider l\'inventaire');
        }
        
        DB::beginTransaction();
        
        try {
            // Mettre à jour le statut de l'inventaire
            $inventory->update([
                'status' => 'valide',
                'validated_by' => auth()->id(),
                'validated_at' => now()
            ]);
            
            // Pour chaque élément avec une différence, créer un mouvement de stock
            foreach ($inventory->items as $item) {
                if ($item->difference != 0) {
                    $type = $item->difference > 0 ? 'entree' : 'sortie';
                    $quantity = abs($item->difference);
                    $motif = 'Ajustement d\'inventaire (Réf: ' . $inventory->reference . ')';
                    
                    StockMovement::create([
                        'type' => $type,
                        'warehouse_id' => $inventory->warehouse_id,
                        'product_id' => $item->product_id,
                        'quantite' => $quantity,
                        'prix_unitaire' => $item->product->prix_achat,
                        'montant_total' => $quantity * $item->product->prix_achat,
                        'motif' => $motif,
                        'created_by' => auth()->id(),
                        'source_type' => 'App\\Models\\StockInventory',
                        'source_id' => $inventory->id
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('success', 'Inventaire validé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la validation de l\'inventaire: ' . $e->getMessage());
        }
    }

    private function calculateTheoreticalStock($productId, $warehouseId)
    {
        // Calculer le stock théorique en fonction des mouvements de stock
        $entrees = StockMovement::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('type', 'entree')
            ->sum('quantite');
            
        $sorties = StockMovement::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('type', 'sortie')
            ->sum('quantite');
            
        return $entrees - $sorties;
    }
}