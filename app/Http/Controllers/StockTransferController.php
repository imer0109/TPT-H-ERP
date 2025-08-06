<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use App\Http\Requests\StockTransferRequest;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with([
            'warehouseSource',
            'warehouseDestination',
            'product',
            'createdBy',
            'validatedBy',
            'receivedBy'
        ])->latest()->paginate(15);

        return view('stock.transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::pluck('nom', 'id');
        $products = Product::pluck('nom', 'id');
        return view('stock.transfers.create', compact('warehouses', 'products'));
    }

    public function store(StockTransferRequest $request)
    {
        $transfer = StockTransfer::create($request->validated() + [
            'created_by' => auth()->id(),
            'statut' => 'en_attente',
            'numero_transfert' => 'TRF-' . date('YmdHis') . '-' . rand(1000, 9999)
        ]);

        return redirect()->route('stock.transfers.index')
            ->with('success', 'Transfert créé avec succès');
    }

    public function show(StockTransfer $transfer)
    {
        $transfer->load([
            'warehouseSource',
            'warehouseDestination',
            'product',
            'createdBy',
            'validatedBy',
            'receivedBy'
        ]);

        return view('stock.transfers.show', compact('transfer'));
    }

    public function validate(StockTransfer $transfer)
    {
        if ($transfer->statut !== 'en_attente') {
            return back()->with('error', 'Ce transfert ne peut plus être validé');
        }

        // Vérifier le stock disponible
        $product = $transfer->product;
        $sourceWarehouse = $transfer->warehouseSource;

        if ($product->getStockInWarehouse($sourceWarehouse->id) < $transfer->quantite) {
            return back()->with('error', 'Stock insuffisant dans le dépôt source');
        }

        $transfer->update([
            'statut' => 'en_transit',
            'validated_by' => auth()->id(),
            'date_validation' => now()
        ]);

        // Créer les mouvements de stock
        $this->createTransferMovements($transfer);

        return back()->with('success', 'Transfert validé avec succès');
    }

    public function receive(StockTransfer $transfer)
    {
        if ($transfer->statut !== 'en_transit') {
            return back()->with('error', 'Ce transfert ne peut pas être réceptionné');
        }

        $transfer->update([
            'statut' => 'receptionne',
            'received_by' => auth()->id(),
            'date_reception' => now()
        ]);

        // Mettre à jour les stocks
        $this->updateStocksOnReceive($transfer);

        return back()->with('success', 'Transfert réceptionné avec succès');
    }

    public function cancel(StockTransfer $transfer)
    {
        if (!in_array($transfer->statut, ['en_attente', 'en_transit'])) {
            return back()->with('error', 'Ce transfert ne peut plus être annulé');
        }

        $transfer->update([
            'statut' => 'annule'
        ]);

        // Si le transfert était en transit, remettre le stock dans le dépôt source
        if ($transfer->statut === 'en_transit') {
            $this->reverseTransferMovements($transfer);
        }

        return back()->with('success', 'Transfert annulé avec succès');
    }

    protected function createTransferMovements(StockTransfer $transfer)
    {
        // Sortie du stock source
        StockMovement::create([
            'warehouse_id' => $transfer->warehouse_source_id,
            'product_id' => $transfer->product_id,
            'type' => 'sortie',
            'quantite' => $transfer->quantite,
            'unite' => $transfer->unite,
            'motif' => 'Transfert vers ' . $transfer->warehouseDestination->nom,
            'reference' => $transfer->numero_transfert,
            'created_by' => auth()->id()
        ]);
    }

    protected function updateStocksOnReceive(StockTransfer $transfer)
    {
        // Entrée dans le stock destination
        StockMovement::create([
            'warehouse_id' => $transfer->warehouse_destination_id,
            'product_id' => $transfer->product_id,
            'type' => 'entree',
            'quantite' => $transfer->quantite,
            'unite' => $transfer->unite,
            'motif' => 'Réception transfert depuis ' . $transfer->warehouseSource->nom,
            'reference' => $transfer->numero_transfert,
            'created_by' => auth()->id()
        ]);
    }

    protected function reverseTransferMovements(StockTransfer $transfer)
    {
        // Annulation de la sortie en créant une entrée dans le stock source
        StockMovement::create([
            'warehouse_id' => $transfer->warehouse_source_id,
            'product_id' => $transfer->product_id,
            'type' => 'entree',
            'quantite' => $transfer->quantite,
            'unite' => $transfer->unite,
            'motif' => 'Annulation transfert ' . $transfer->numero_transfert,
            'reference' => $transfer->numero_transfert,
            'created_by' => auth()->id()
        ]);
    }
}