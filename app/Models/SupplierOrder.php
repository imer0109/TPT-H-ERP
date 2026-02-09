<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_request_id','fournisseur_id','agency_id','code','date_commande','statut',
        'nature_achat','adresse_livraison','delai_contractuel','conditions_paiement',
        'montant_ht','montant_tva','montant_ttc','tva_percentage','devise','notes','created_by'
    ];

    protected $casts = [
        'date_commande' => 'date',
        'delai_contractuel' => 'date',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'tva_percentage' => 'decimal:2'
    ];

    const STATUTS = [
        'Brouillon' => 'Brouillon',
        'En attente' => 'En attente',
        'Envoyé' => 'Envoyé',
        'Confirmé' => 'Confirmé',
        'Livré' => 'Livré',
        'Clôturé' => 'Clôturé',
        'Annulé' => 'Annulé'
    ];

    const NATURE_ACHATS = [
        'Bien' => 'Bien',
        'Service' => 'Service'
    ];

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SupplierOrderItem::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(SupplierDelivery::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SupplierInvoice::class, 'supplier_order_id');
    }

    // Get the company associated with this order (through agency or purchase request)
    public function company(): BelongsTo
    {
        // Try to get company through agency first
        if ($this->agency) {
            return $this->agency->company();
        }
        
        // If no agency, try through purchase request
        if ($this->purchaseRequest) {
            return $this->purchaseRequest->company();
        }
        
        // Fallback to direct relationship if exists
        return $this->belongsTo(Company::class);
    }

    // Calculate total amount with a specific TVA percentage
    public function calculateAmounts($tvaPercentage = 18.00)
    {
        $montant_ht = 0;
        foreach ($this->items as $item) {
            $montant_ht += $item->montant_total;
        }
        
        $montant_tva = $montant_ht * ($tvaPercentage / 100);
        $montant_ttc = $montant_ht + $montant_tva;
        
        return [
            'montant_ht' => $montant_ht,
            'montant_tva' => $montant_tva,
            'montant_ttc' => $montant_ttc,
            'tva_percentage' => $tvaPercentage
        ];
    }

    // Get formatted status with colors for display
    public function getFormattedStatus()
    {
        $status = $this->statut;
        $colors = [
            'Brouillon' => 'bg-gray-100 text-gray-800',
            'En attente' => 'bg-yellow-100 text-yellow-800',
            'Envoyé' => 'bg-blue-100 text-blue-800',
            'Confirmé' => 'bg-green-100 text-green-800',
            'Livré' => 'bg-purple-100 text-purple-800',
            'Clôturé' => 'bg-gray-100 text-gray-800',
            'Annulé' => 'bg-red-100 text-red-800'
        ];
        
        return [
            'text' => $status,
            'color' => $colors[$status] ?? 'bg-gray-100 text-gray-800'
        ];
    }

    // Check if order is fully delivered
    public function isFullyDelivered(): bool
    {
        // Get all deliveries for this order that are validated
        $validatedDeliveries = $this->deliveries->where('statut', 'validated');
        
        if ($validatedDeliveries->isEmpty()) {
            return false;
        }
        
        // Check if all items are delivered
        $totalOrdered = $this->items->sum('quantite');
        $totalDelivered = $validatedDeliveries->sum(function ($delivery) {
            return $delivery->items->sum('quantite_livree');
        });
        
        return $totalDelivered >= $totalOrdered;
    }

    // Get delivery status
    public function getDeliveryStatus()
    {
        $deliveries = $this->deliveries;
        
        if ($deliveries->isEmpty()) {
            return 'non_livre';
        }
        
        $validatedDeliveries = $deliveries->where('statut', 'validated');
        $rejectedDeliveries = $deliveries->where('statut', 'rejected');
        
        if ($validatedDeliveries->count() == $deliveries->count()) {
            return 'livre';
        } elseif ($rejectedDeliveries->count() > 0) {
            return 'probleme';
        } else {
            return 'partiel';
        }
    }
}