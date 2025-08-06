<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'trial_period_start',
        'trial_period_end',
        'base_salary',
        'benefits',
        'contract_file',
        'hiring_form',
        'supporting_documents'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'trial_period_start' => 'date',
        'trial_period_end' => 'date',
        'benefits' => 'json',
        'supporting_documents' => 'json',
        'base_salary' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function isActive()
    {
        return $this->start_date <= now() && 
               ($this->end_date === null || $this->end_date >= now());
    }

    public function isInTrialPeriod()
    {
        if (!$this->trial_period_start || !$this->trial_period_end) {
            return false;
        }

        return now()->between($this->trial_period_start, $this->trial_period_end);
    }
}