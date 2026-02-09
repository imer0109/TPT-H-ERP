<?php

namespace App\Http\Controllers;

use App\Models\StockAlert;
use App\Models\Product;
use App\Models\Warehouse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StockAlertRequest;

class StockAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockAlert::with(['product', 'warehouse', 'createdBy']);
        
        // Filtres
        if ($request->has('warehouse_id') && $request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('alerte_active')) {
            $query->where('alerte_active', $request->boolean('alerte_active'));
        }
        
        $alerts = $query->latest()->paginate(15);
        $warehouses = Warehouse::pluck('nom', 'id');
        $products = Product::pluck('name', 'id');
        
        return view('stock.alerts.index', compact('alerts', 'warehouses', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::pluck('name', 'id');
        $warehouses = Warehouse::pluck('nom', 'id');
        
        return view('stock.alerts.create', compact('products', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockAlertRequest $request)
    {
        // Vérifier si une alerte existe déjà pour ce produit et ce dépôt
        $existingAlert = StockAlert::where('product_id', $request->product_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->first();
            
        if ($existingAlert) {
            return redirect()->route('stock.alerts.index')
                ->with('error', 'Une alerte existe déjà pour ce produit dans ce dépôt.');
        }
        
        // Map request fields to database columns
        $alertData = $request->validated();
        $alertData['email_notification'] = $alertData['email_notification'] ?? false; // Changed from email_notifications
        $alertData['seuil_minimum'] = $alertData['minimum_threshold'];
        $alertData['seuil_securite'] = $alertData['security_threshold'];
        $alertData['alerte_active'] = $alertData['is_active'] ?? false;
        
        // Remove the request-specific fields
        unset($alertData['minimum_threshold'], $alertData['security_threshold'], $alertData['is_active']);
        
        $alert = new StockAlert($alertData);
        $alert->created_by = auth()->id();
        $alert->save();
        
        return redirect()->route('stock.alerts.index')
            ->with('success', 'Alerte de stock créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAlert $stockAlert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockAlert $stockAlert)
    {
        // Debug: Check if the model is loaded correctly
        if (!$stockAlert || !$stockAlert->exists) {
            return redirect()->route('stock.alerts.index')
                ->with('error', 'Alerte de stock non trouvée.');
        }
        
        $products = Product::pluck('name', 'id');
        $warehouses = Warehouse::pluck('nom', 'id');
        
        return view('stock.alerts.edit', compact('stockAlert', 'products', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockAlertRequest $request, StockAlert $stockAlert)
    {
        // Debug: Check if the model is loaded correctly
        if (!$stockAlert || !$stockAlert->exists) {
            return redirect()->route('stock.alerts.index')
                ->with('error', 'Alerte de stock non trouvée.');
        }
        
        // Vérifier si une autre alerte existe déjà pour ce produit et ce dépôt
        $existingAlert = StockAlert::where('product_id', $request->product_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('id', '!=', $stockAlert->id)
            ->first();
            
        if ($existingAlert) {
            return redirect()->route('stock.alerts.edit', $stockAlert)
                ->with('error', 'Une autre alerte existe déjà pour ce produit dans ce dépôt.');
        }
        
        // Map request fields to database columns
        $alertData = $request->validated();
        $alertData['email_notification'] = $alertData['email_notification'] ?? false; // Changed from email_notifications
        $alertData['seuil_minimum'] = $alertData['minimum_threshold'];
        $alertData['seuil_securite'] = $alertData['security_threshold'];
        $alertData['alerte_active'] = $alertData['is_active'] ?? false;
        
        // Remove the request-specific fields
        unset($alertData['minimum_threshold'], $alertData['security_threshold'], $alertData['is_active']);
        
        $stockAlert->update($alertData);
        
        return redirect()->route('stock.alerts.index')
            ->with('success', 'Alerte de stock mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockAlert $stockAlert)
    {
        // Debug: Check if the model is loaded correctly
        if (!$stockAlert || !$stockAlert->exists) {
            return redirect()->route('stock.alerts.index')
                ->with('error', 'Alerte de stock non trouvée.');
        }
        
        $stockAlert->delete();
        
        return redirect()->route('stock.alerts.index')
            ->with('success', 'Alerte de stock supprimée avec succès.');
    }
    
    /**
     * Toggle the active status of the alert.
     */
    public function toggleStatus(StockAlert $stockAlert)
    {
        // Debug: Check if the model is loaded correctly
        if (!$stockAlert || !$stockAlert->exists) {
            return redirect()->route('stock.alerts.index')
                ->with('error', 'Alerte de stock non trouvée.');
        }
        
        $stockAlert->alerte_active = !$stockAlert->alerte_active;
        $stockAlert->save();
        
        return redirect()->route('stock.alerts.index')
            ->with('success', 'Statut de l\'alerte modifié avec succès.');
    }
    
    /**
     * Toggle email notifications for the alert.
     */
    public function toggleNotifications(StockAlert $stockAlert)
    {
        // Debug: Check if the model is loaded correctly
        if (!$stockAlert || !$stockAlert->exists) {
            return redirect()->route('stock.alerts.index')
                ->with('error', 'Alerte de stock non trouvée.');
        }
        
        $stockAlert->email_notification = !$stockAlert->email_notification; // Changed from email_notifications
        $stockAlert->save();
        
        return redirect()->route('stock.alerts.index')
            ->with('success', 'Notifications par email modifiées avec succès.');
    }
}
