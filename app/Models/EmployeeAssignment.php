<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'company_id',
        'agency_id',
        'warehouse_id',
        'position_id',
        'start_date',
        'end_date',
        'is_primary',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_primary' => 'boolean'
    ];

    protected $attributes = [
        'status' => 'active'
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'suspended' => 'Suspendu',
            default => 'Inconnu'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'warning',
            default => 'secondary'
        };
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->start_date === null || $this->start_date <= now()) && 
               ($this->end_date === null || $this->end_date >= now());
    }

    public function isExpired()
    {
        return $this->end_date !== null && $this->end_date < now();
    }

    public function isFuture()
    {
        return $this->start_date !== null && $this->start_date > now();
    }
}