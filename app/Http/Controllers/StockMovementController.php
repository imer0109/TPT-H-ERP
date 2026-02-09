<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\StockMovementRequest;
use Illuminate\Support\Facades\Log;
// use Maatwebsite\Excel\Facades\Excel;
use App\Services\ExcelService as Excel;
use App\Imports\StockMovementsImport;
use App\Exports\StockMovementsExport;
use App\Http\Controllers\Controller;


class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['warehouse', 'product', 'createdBy', 'validatedBy']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->has('date_start') && $request->has('date_end')) {
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        $movements = $query->latest()->paginate(15);
        $warehouses = Warehouse::pluck('nom', 'id');

        return view('stock.movements.index', compact('movements', 'warehouses'));
    }

    public function create()
    {
        $products = Product::pluck('name', 'id');
        $warehouses = Warehouse::pluck('nom', 'id');
        return view('stock.movements.create', compact('products', 'warehouses'));
    }

    public function store(StockMovementRequest $request)
    {
        // Debug: Log the request data
        Log::info('StockMovement store request data:', $request->all());
        Log::info('StockMovement validated data:', $request->validated());
        
        // The montant_total is now coming from the form
        $movement = StockMovement::create($request->validated() + [
            'created_by' => auth()->id()
        ]);

        // Mettre à jour le stock du produit
        $this->updateProductStock($movement);

        return redirect()->route('stock.movements.index')->with('success', 'Mouvement de stock enregistré avec succès');
    }

    public function show(StockMovement $movement)
    {
        $movement->load(['warehouse', 'product', 'createdBy', 'validatedBy', 'sourceEntity', 'destinationEntity']);
        return view('stock.movements.show', compact('movement'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new StockMovementsImport, $request->file('file'));
        
        return redirect()->route('stock.movements.index')->with('success', 'Import terminé avec succès');
    }

    public function export(Request $request)
    {
        return Excel::download(new StockMovementsExport, 'mouvements-stock.xlsx');
    }

    private function updateProductStock(StockMovement $movement)
    {
        $product = $movement->product;
        $quantity = $movement->quantite;

        if ($movement->type === 'entree') {
            $product->increment('quantite', $quantity);
        } else if ($movement->type === 'sortie') {
            $product->decrement('quantite', $quantity);
        }

        // Vérifier les alertes de stock
        $this->checkStockAlerts($product, $movement->warehouse_id);
    }

    private function checkStockAlerts($product, $warehouseId)
    {
        $alert = $product->alerts()->where('warehouse_id', $warehouseId)->first();

        if ($alert && $alert->alerte_active) {
            if ($product->quantite <= $alert->seuil_minimum) {
                // Envoyer notification de rupture de stock
                // TODO: Implémenter la notification
            } else if ($product->quantite <= $alert->seuil_securite) {
                // Envoyer notification de stock bas
                // TODO: Implémenter la notification
            }
        }
    }
}