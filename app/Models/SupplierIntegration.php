<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierIntegration extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'supplier_integrations';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'fournisseur_id',
        'integration_type',
        'external_system',
        'external_id',
        'is_active',
        'sync_status',
        'last_sync_at',
        'sync_error_message',
        'configuration',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'last_sync_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'configuration' => 'array',
    ];

    /**
     * Relation avec le fournisseur.
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    /**
     * Scope pour filtrer les intégrations actives.
     */
    public function scopeActives($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par type d'intégration.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('integration_type', $type);
    }

    /**
     * Accesseur pour le statut de synchronisation formaté.
     */
    public function getSyncStatusFormattedAttribute()
    {
        $statuses = [
            'synced' => 'Synchronisé',
            'pending' => 'En attente',
            'failed' => 'Échoué'
        ];
        return $statuses[$this->sync_status] ?? ucfirst($this->sync_status);
    }

    /**
     * Accesseur pour le type d'intégration formaté.
     */
    public function getIntegrationTypeFormattedAttribute()
    {
        $types = [
            'erp' => 'ERP',
            'accounting' => 'Comptabilité',
            'inventory' => 'Gestion de stock',
            'custom' => 'Personnalisé'
        ];
        return $types[$this->integration_type] ?? ucfirst($this->integration_type);
    }
}