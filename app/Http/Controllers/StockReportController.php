<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function currentStock(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->has('warehouse_id') && $request->warehouse_id) {
            // Si un dépôt spécifique est sélectionné, nous devons filtrer les produits
            // qui ont des mouvements dans ce dépôt
            $warehouseId = $request->warehouse_id;
            $query->whereHas('stockMovements', function($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            });
        }
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $query->paginate(15);
        $warehouses = Warehouse::where('actif', true)->pluck('nom', 'id');
        
        return view('stock.reports.current-stock', compact('products', 'warehouses'));
    }

    public function movementsHistory(Request $request)
    {
        $query = StockMovement::with(['warehouse', 'product', 'createdBy']);
        
        if ($request->has('warehouse_id') && $request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $movements = $query->orderBy('created_at', 'desc')->paginate(15);
        $warehouses = Warehouse::where('actif', true)->pluck('nom', 'id');
        $products = Product::where('actif', true)->pluck('nom', 'id');
        
        return view('stock.reports.movements-history', compact('movements', 'warehouses', 'products'));
    }

    public function valuation(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        
        $query = DB::table('products')
            ->select(
                'products.id',
                'products.nom',
                'products.reference',
                'products.prix_achat',
                'products.stock_actuel',
                DB::raw('products.prix_achat * products.stock_actuel as valeur_stock')
            )
            ->where('products.actif', true);
        
        if ($warehouseId) {
            // Si un dépôt spécifique est sélectionné, nous devons calculer le stock
            // pour ce dépôt spécifique
            $query = DB::table('stock_movements')
                ->select(
                    'products.id',
                    'products.nom',
                    'products.reference',
                    'products.prix_achat',
                    DB::raw('SUM(CASE WHEN stock_movements.type = "entree" THEN stock_movements.quantite ELSE -stock_movements.quantite END) as stock_actuel'),
                    DB::raw('products.prix_achat * SUM(CASE WHEN stock_movements.type = "entree" THEN stock_movements.quantite ELSE -stock_movements.quantite END) as valeur_stock')
                )
                ->join('products', 'stock_movements.product_id', '=', 'products.id')
                ->where('stock_movements.warehouse_id', $warehouseId)
                ->where('products.actif', true)
                ->groupBy('products.id', 'products.nom', 'products.reference', 'products.prix_achat');
        }
        
        $products = $query->paginate(15);
        $warehouses = Warehouse::where('actif', true)->pluck('nom', 'id');
        
        $totalValuation = $products->sum('valeur_stock');
        
        return view('stock.reports.valuation', compact('products', 'warehouses', 'totalValuation'));
    }

    public function losses(Request $request)
    {
        $query = StockMovement::with(['warehouse', 'product', 'createdBy'])
            ->where('type', 'sortie')
            ->where('motif', 'like', '%perte%');
        
        if ($request->has('warehouse_id') && $request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $losses = $query->orderBy('created_at', 'desc')->paginate(15);
        $warehouses = Warehouse::where('actif', true)->pluck('nom', 'id');
        
        $totalLoss = $losses->sum('montant_total');
        
        return view('stock.reports.losses', compact('losses', 'warehouses', 'totalLoss'));
    }
}