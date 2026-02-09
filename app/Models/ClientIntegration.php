<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientIntegration extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'client_id',
        'integration_type', // crm, marketing, erp, etc.
        'external_id', // ID in the external system
        'external_system', // name of the external system (Mailchimp, WhatsApp Business, etc.)
        'sync_status', // pending, synced, failed
        'last_sync_at',
        'sync_error_message',
        'mapping_data', // JSON field for field mappings
        'is_active'
    ];
    
    protected $casts = [
        'last_sync_at' => 'datetime',
        'mapping_data' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}