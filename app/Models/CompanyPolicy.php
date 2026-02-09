<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPolicy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'agency_id',
        'policy_type', // approval, cash_management, payroll, etc.
        'title',
        'description',
        'is_active',
        'effective_date',
        'expiry_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    // A policy can belong to a company or an agency
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}