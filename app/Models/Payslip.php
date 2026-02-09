<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payslip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'employee_id',
        'company_id',
        'period_start',
        'period_end',
        'base_salary',
        'gross_salary',
        'net_salary',
        'earnings',
        'deductions',
        'overtime_hours',
        'overtime_rate',
        'transport_allowance',
        'housing_allowance',
        'meal_allowance',
        'performance_bonus',
        'social_security',
        'income_tax',
        'total_deductions',
        'other_allowances',
        'allowances_description',
        'advance_deduction',
        'loan_deduction',
        'other_deductions',
        'deductions_description',
        'notes',
        'payment_method',
        'payment_reference',
        'status',
        'pdf_file',
        'generated_by',
        'validated_by',
        'validated_at',
        'paid_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'earnings' => 'json',
        'deductions' => 'json',
        'base_salary' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'meal_allowance' => 'decimal:2',
        'performance_bonus' => 'decimal:2',
        'social_security' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'advance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'validated_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function calculateTotalEarnings()
    {
        return array_sum($this->earnings);
    }

    public function calculateTotalDeductions()
    {
        return array_sum($this->deductions);
    }

    public function generateReference()
    {
        $date = now()->format('Ym');
        $count = static::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
        
        return sprintf('PAY-%s-%04d', $date, $count);
    }
}