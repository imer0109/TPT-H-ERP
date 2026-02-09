<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_days',
        'is_paid',
        'requires_approval',
        'affects_salary'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'requires_approval' => 'boolean',
        'affects_salary' => 'boolean',
    ];

    // Relations
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
    
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return $this->is_paid ? 'Payé' : 'Non payé';
    }

    public function getApprovalTextAttribute()
    {
        return $this->requires_approval ? 'Approbation requise' : 'Approbation non requise';
    }
}