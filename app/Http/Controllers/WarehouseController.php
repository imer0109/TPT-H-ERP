<?php

namespace App\Http\Controllers;
use App\Models\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;

use Illuminate\Http\Request;

class WarehouseController extends Controller
{
     public function index()
    {
        $warehouses = Warehouse::with(['entity', 'createdBy'])->paginate(10);
        return view('stock.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('stock.warehouses.create');
    }

    public function store(WarehouseRequest $request)
    {
        $warehouse = Warehouse::create($request->validated() + ['created_by' => auth()->id()]);
        return redirect()->route('stock.warehouses.index')->with('success', 'Dépôt créé avec succès');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['entity', 'createdBy', 'stockMovements', 'alerts']);
        return view('stock.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('stock.warehouses.edit', compact('warehouse'));
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());
        return redirect()->route('stock.warehouses.index')->with('success', 'Dépôt mis à jour avec succès');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('stock.warehouses.index')->with('success', 'Dépôt supprimé avec succès');
    }
}
