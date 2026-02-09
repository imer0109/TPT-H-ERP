<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'calculation_type',
        'calculation_value',
        'is_taxable',
        'affects_gross',
        'is_mandatory',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_taxable' => 'boolean',
        'affects_gross' => 'boolean',
        'is_mandatory' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope to get only active payroll items.
     */
    public function scopeActive($query)
    {
        // Since there's no 'active' field in the migration, we'll consider all items as active
        // or you can add this field to the migration if needed
        return $query;
    }

    /**
     * Scope to get earning items.
     */
    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    /**
     * Scope to get deduction items.
     */
    public function scopeDeductions($query)
    {
        return $query->where('type', 'deduction');
    }

    /**
     * Scope to get mandatory items.
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Scope to get items ordered by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    /**
     * Get the formatted type name.
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'earning' => 'Gain',
            'deduction' => 'Déduction',
            default => $this->type,
        };
    }

    /**
     * Get the formatted calculation type name.
     */
    public function getCalculationTypeNameAttribute()
    {
        return match($this->calculation_type) {
            'fixed' => 'Montant fixe',
            'percentage' => 'Pourcentage',
            'formula' => 'Formule',
            default => $this->calculation_type,
        };
    }

    /**
     * Calculate the value for a given base amount.
     */
    public function calculateValue($baseAmount = 0)
    {
        return match($this->calculation_type) {
            'fixed' => (float) $this->calculation_value,
            'percentage' => $baseAmount * ((float) $this->calculation_value / 100),
            'formula' => $this->evaluateFormula($this->calculation_value, $baseAmount),
            default => 0,
        };
    }

    /**
     * Evaluate a formula (basic implementation).
     */
    protected function evaluateFormula($formula, $baseAmount)
    {
        // Replace variables in the formula
        $formula = str_replace('{base}', $baseAmount, $formula);
        
        // For security, only allow basic mathematical operations
        // This is a simplified implementation - in production, you'd want a more robust formula parser
        if (preg_match('/^[\d\+\-\*\/\(\)\.\s]+$/', $formula)) {
            try {
                return eval("return $formula;");
            } catch (Exception $e) {
                return 0;
            }
        }
        
        return 0;
    }

    /**
     * Check if this item is an earning.
     */
    public function isEarning()
    {
        return $this->type === 'earning';
    }

    /**
     * Check if this item is a deduction.
     */
    public function isDeduction()
    {
        return $this->type === 'deduction';
    }

    /**
     * Check if this item is taxable.
     */
    public function isTaxable()
    {
        return $this->is_taxable;
    }

    /**
     * Check if this item affects gross salary.
     */
    public function affectsGross()
    {
        return $this->affects_gross;
    }
}