<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRegulation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'agency_id',
        'tax_type', // VAT, IS, CNSS, etc.
        'rate',
        'description',
        'is_active',
        'effective_date',
        'expiry_date',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    // A tax regulation can belong to a company or an agency
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}