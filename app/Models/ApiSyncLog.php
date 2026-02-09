<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiSyncLog extends Model
{
    protected $fillable = [
        'id',
        'connector_id',
        'type',
        'direction',
        'status',
        'started_at',
        'completed_at',
        'records_processed',
        'records_successful',
        'records_failed',
        'error_message',
        'error_details',
        'request_data',
        'response_data',
        'execution_time',
        'triggered_by'
    ];

    protected $casts = [
        'id' => 'string',
        'triggered_by' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'records_processed' => 'integer',
        'records_successful' => 'integer',
        'records_failed' => 'integer',
        'request_data' => 'array',
        'response_data' => 'array',
        'error_details' => 'array',
        'execution_time' => 'float'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Sync types
    const TYPE_SCHEDULED = 'scheduled';
    const TYPE_MANUAL = 'manual';
    const TYPE_WEBHOOK = 'webhook';
    const TYPE_EVENT = 'event';

    // Sync directions
    const DIRECTION_INBOUND = 'inbound';
    const DIRECTION_OUTBOUND = 'outbound';

    // Sync statuses
    const STATUS_RUNNING = 'running';
    const STATUS_SUCCESS = 'success';
    const STATUS_PARTIAL = 'partial';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->started_at)) {
                $model->started_at = now();
            }
        });
    }

    /**
     * Get the connector that owns this log
     */
    public function connector(): BelongsTo
    {
        return $this->belongsTo(ApiConnector::class, 'connector_id');
    }

    /**
     * Get the user who triggered the sync
     */
    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    /**
     * Get available sync types
     */
    public static function getSyncTypes(): array
    {
        return [
            self::TYPE_SCHEDULED => 'Planifié',
            self::TYPE_MANUAL => 'Manuel',
            self::TYPE_WEBHOOK => 'Webhook',
            self::TYPE_EVENT => 'Événement'
        ];
    }

    /**
     * Get available directions
     */
    public static function getDirections(): array
    {
        return [
            self::DIRECTION_INBOUND => 'Entrant (vers ERP)',
            self::DIRECTION_OUTBOUND => 'Sortant (depuis ERP)'
        ];
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_RUNNING => 'En cours',
            self::STATUS_SUCCESS => 'Succès',
            self::STATUS_PARTIAL => 'Partiel',
            self::STATUS_FAILED => 'Échec',
            self::STATUS_CANCELLED => 'Annulé'
        ];
    }

    /**
     * Mark sync as completed
     */
    public function markCompleted(string $status, ?string $errorMessage = null): void
    {
        $this->update([
            'status' => $status,
            'completed_at' => now(),
            'execution_time' => $this->started_at ? now()->diffInSeconds($this->started_at) : 0,
            'error_message' => $errorMessage
        ]);
    }

    /**
     * Update processing stats
     */
    public function updateStats(int $processed, int $successful, int $failed): void
    {
        $this->update([
            'records_processed' => $processed,
            'records_successful' => $successful,
            'records_failed' => $failed
        ]);
    }

    /**
     * Check if sync was successful
     */
    public function wasSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Check if sync had errors
     */
    public function hasErrors(): bool
    {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_PARTIAL]);
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRate(): float
    {
        if ($this->records_processed === 0) {
            return 0;
        }
        
        return round(($this->records_successful / $this->records_processed) * 100, 2);
    }

    /**
     * Get type label
     */
    public function getTypeLabel(): string
    {
        return self::getSyncTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get direction label
     */
    public function getDirectionLabel(): string
    {
        return self::getDirections()[$this->direction] ?? $this->direction;
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_SUCCESS => 'badge-success',
            self::STATUS_PARTIAL => 'badge-warning',
            self::STATUS_FAILED => 'badge-danger',
            self::STATUS_RUNNING => 'badge-info',
            self::STATUS_CANCELLED => 'badge-secondary',
            default => 'badge-light'
        };
    }

    /**
     * Get formatted execution time
     */
    public function getFormattedExecutionTime(): string
    {
        if ($this->execution_time < 60) {
            return round($this->execution_time, 2) . 's';
        }
        
        $minutes = floor($this->execution_time / 60);
        $seconds = $this->execution_time % 60;
        
        return $minutes . 'm ' . round($seconds, 2) . 's';
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('started_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for failed logs
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', [self::STATUS_FAILED, self::STATUS_PARTIAL]);
    }

    /**
     * Scope for successful logs
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }
}