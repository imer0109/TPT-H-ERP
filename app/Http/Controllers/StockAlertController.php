<?php

namespace App\Http\Controllers;

use App\Models\StockAlert;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\StockAlertRequest;

class StockAlertController extends Controller
{
    public function index()
    {
        $alerts = StockAlert::with(['product', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('stock.alerts.index', compact('alerts'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('stock.alerts.create', compact('products', 'warehouses'));
    }

    public function store(StockAlertRequest $request)
    {
        $alert = StockAlert::create($request->validated());

        return redirect()
            ->route('stock.alerts.index')
            ->with('success', 'Alerte de stock créée avec succès.');
    }

    public function edit(StockAlert $alert)
    {
        $products = Product::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('stock.alerts.edit', compact('alert', 'products', 'warehouses'));
    }

    public function update(StockAlertRequest $request, StockAlert $alert)
    {
        $alert->update($request->validated());

        return redirect()
            ->route('stock.alerts.index')
            ->with('success', 'Alerte de stock mise à jour avec succès.');
    }

    public function destroy(StockAlert $alert)
    {
        $alert->delete();

        return redirect()
            ->route('stock.alerts.index')
            ->with('success', 'Alerte de stock supprimée avec succès.');
    }

    public function toggleStatus(StockAlert $alert)
    {
        $alert->update([
            'is_active' => !$alert->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès.'
        ]);
    }

    public function toggleNotifications(StockAlert $alert)
    {
        $alert->update([
            'email_notifications' => !$alert->email_notifications
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Préférences de notification mises à jour avec succès.'
        ]);
    }
}