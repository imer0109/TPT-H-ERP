<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiConnector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'type',
        'description',
        'configuration',
        'mapping_rules',
        'sync_frequency',
        'last_sync_at',
        'next_sync_at',
        'status',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'string',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'configuration' => 'array',
        'mapping_rules' => 'array',
        'last_sync_at' => 'datetime',
        'next_sync_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Connector types
    const TYPE_SAGE = 'sage';
    const TYPE_EBP = 'ebp';
    const TYPE_GOOGLE_SHEETS = 'google_sheets';
    const TYPE_EXCEL = 'excel';
    const TYPE_POS = 'pos';
    const TYPE_PAYROLL = 'payroll';
    const TYPE_CRM = 'crm';
    const TYPE_CUSTOM = 'custom';

    // Status types
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ERROR = 'error';
    const STATUS_SYNCING = 'syncing';

    // Sync frequencies (in minutes)
    const FREQ_5MIN = 5;
    const FREQ_30MIN = 30;
    const FREQ_1HOUR = 60;
    const FREQ_6HOURS = 360;
    const FREQ_12HOURS = 720;
    const FREQ_DAILY = 1440;
    const FREQ_WEEKLY = 10080;
    const FREQ_MONTHLY = 43200;
    const FREQ_MANUAL = 0;

    // Data directions
    const DIRECTION_INBOUND = 'inbound';
    const DIRECTION_OUTBOUND = 'outbound';
    const DIRECTION_BIDIRECTIONAL = 'bidirectional';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the company that owns the connector
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the synchronization logs
     */
    public function syncLogs(): HasMany
    {
        return $this->hasMany(ApiSyncLog::class, 'connector_id');
    }

    /**
     * Get the data mappings
     */
    public function dataMappings(): HasMany
    {
        return $this->hasMany(ApiDataMapping::class, 'connector_id');
    }

    /**
     * Get the user who created the connector
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the connector
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get available connector types
     */
    public static function getConnectorTypes(): array
    {
        return [
            self::TYPE_SAGE => 'SAGE (Comptabilité/Paie)',
            self::TYPE_EBP => 'EBP (Comptabilité/Gestion)',
            self::TYPE_GOOGLE_SHEETS => 'Google Sheets',
            self::TYPE_EXCEL => 'Microsoft Excel/CSV',
            self::TYPE_POS => 'Point de Vente (POS)',
            self::TYPE_PAYROLL => 'Système de Paie',
            self::TYPE_CRM => 'CRM/Gestion Client',
            self::TYPE_CUSTOM => 'Connecteur Personnalisé'
        ];
    }

    /**
     * Get available sync frequencies
     */
    public static function getSyncFrequencies(): array
    {
        return [
            self::FREQ_MANUAL => 'Manuel (À la demande)',
            self::FREQ_5MIN => 'Toutes les 5 minutes',
            self::FREQ_30MIN => 'Toutes les 30 minutes',
            self::FREQ_1HOUR => 'Toutes les heures',
            self::FREQ_6HOURS => 'Toutes les 6 heures',
            self::FREQ_12HOURS => 'Toutes les 12 heures',
            self::FREQ_DAILY => 'Quotidien',
            self::FREQ_WEEKLY => 'Hebdomadaire',
            self::FREQ_MONTHLY => 'Mensuel'
        ];
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_INACTIVE => 'Inactif',
            self::STATUS_ERROR => 'En erreur',
            self::STATUS_SYNCING => 'Synchronisation en cours'
        ];
    }

    /**
     * Check if connector is ready for sync
     */
    public function isReadyForSync(): bool
    {
        if (!$this->is_active || $this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->sync_frequency == self::FREQ_MANUAL) {
            return false;
        }

        return $this->next_sync_at && $this->next_sync_at <= now();
    }

    /**
     * Calculate next sync time
     */
    public function calculateNextSync(): void
    {
        if ($this->sync_frequency == self::FREQ_MANUAL) {
            $this->next_sync_at = null;
        } else {
            $this->next_sync_at = now()->addMinutes($this->sync_frequency);
        }
    }

    /**
     * Get connector configuration value
     */
    public function getConfig(string $key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }

    /**
     * Set connector configuration value
     */
    public function setConfig(string $key, $value): void
    {
        $config = $this->configuration ?? [];
        data_set($config, $key, $value);
        $this->configuration = $config;
    }

    /**
     * Get mapping rule
     */
    public function getMappingRule(string $field): ?array
    {
        return data_get($this->mapping_rules, $field);
    }

    /**
     * Set mapping rule
     */
    public function setMappingRule(string $field, array $rule): void
    {
        $rules = $this->mapping_rules ?? [];
        $rules[$field] = $rule;
        $this->mapping_rules = $rules;
    }

    /**
     * Update last sync timestamp
     */
    public function updateLastSync(): void
    {
        $this->last_sync_at = now();
        $this->calculateNextSync();
        $this->save();
    }

    /**
     * Get connector type label
     */
    public function getTypeLabel(): string
    {
        return self::getConnectorTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get frequency label
     */
    public function getFrequencyLabel(): string
    {
        return self::getSyncFrequencies()[$this->sync_frequency] ?? 'Inconnu';
    }

    /**
     * Scope for active connectors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for ready to sync connectors
     */
    public function scopeReadyForSync($query)
    {
        return $query->active()
            ->where('sync_frequency', '>', 0)
            ->where(function($q) {
                $q->whereNull('next_sync_at')
                  ->orWhere('next_sync_at', '<=', now());
            });
    }
}