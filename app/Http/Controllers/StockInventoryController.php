<?php

namespace App\Http\Controllers;

use App\Models\StockInventory;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StockInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StockInventory::with(['warehouse', 'createdBy']);
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('warehouse', function($w) use ($search) {
                      $w->where('nom', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }
        
        $inventories = $query->orderBy('created_at', 'desc')->paginate(15);
        $warehouses = \App\Models\Warehouse::orderBy('nom')->pluck('nom', 'id');
        
        return view('stock.inventories.index', compact('inventories', 'warehouses'));
    }
    
    public function create()
    {
        $warehouses = \App\Models\Warehouse::orderBy('nom')->pluck('nom', 'id');
        return view('stock.inventories.create', compact('warehouses'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'notes' => 'nullable|string|max:500',
            'date' => 'required|date'
        ]);
        
        try {
            $inventory = StockInventory::create([
                'reference' => 'INV-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4)),
                'warehouse_id' => $validated['warehouse_id'],
                'notes' => $validated['notes'],
                'date' => $validated['date'],
                'status' => 'en_cours',
                'created_by' => Auth::id()
            ]);
            
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('success', 'Inventaire créé avec succès');
        } catch (\Exception $e) {
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
                ->with('error', 'Cet inventaire ne peut pas être modifié');
        }
        
        $inventory->load(['warehouse', 'items.product']);
        return view('stock.inventories.edit', compact('inventory'));
    }
    
    public function update(Request $request, StockInventory $inventory)
    {
        if ($inventory->status !== 'en_cours') {
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('error', 'Cet inventaire ne peut pas être modifié');
        }
        
        try {
            foreach ($request->input('items', []) as $itemId => $data) {
                $item = $inventory->items()->find($itemId);
                if ($item) {
                    $item->update([
                        'actual_quantity' => $data['actual_quantity'] ?? null,
                        'comment' => $data['comment'] ?? null
                    ]);
                }
            }
            
            return redirect()->route('stock.inventories.show', $inventory)
                ->with('success', 'Inventaire mis à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'inventaire: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function validateInventory(StockInventory $inventory)
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
                'validated_by' => Auth::id(),
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
                        'created_by' => Auth::id(),
                        'source_type' => 'App\\Models\\StockInventory',
                        'source_id' => $inventory->id
                    ]);
                }
            }
            
            DB::commit();
            
            // Recharger l'inventaire avec ses relations pour l'affichage
            $inventory->load(['warehouse', 'createdBy', 'validatedBy', 'items.product']);
            
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
    
    public function generatePdf(StockInventory $inventory)
    {
        $inventory->load(['warehouse', 'createdBy', 'validatedBy', 'items.product']);
        
        // Utilisation de la façade PDF correctement configurée
        $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
        $pdf->loadView('stock.inventories.pdf', compact('inventory'));
        
        return $pdf->download('inventaire-' . $inventory->reference . '.pdf');
    }
}