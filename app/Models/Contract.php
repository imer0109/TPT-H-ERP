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
        'supporting_documents',
        'status',
        'termination_reason',
        'terminated_at',
        'terminated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'trial_period_start' => 'date',
        'trial_period_end' => 'date',
        'benefits' => 'json',
        'supporting_documents' => 'json',
        'base_salary' => 'decimal:2',
        'terminated_at' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function terminatedBy()
    {
        return $this->belongsTo(User::class, 'terminated_by');
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               ($this->end_date === null || $this->end_date >= now());
    }

    public function isInTrialPeriod()
    {
        if (!$this->trial_period_start || !$this->trial_period_end) {
            return false;
        }

        return now()->between($this->trial_period_start, $this->trial_period_end);
    }

    public function isTerminated()
    {
        return $this->status === 'terminated' && $this->terminated_at !== null;
    }

    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'active':
                return 'bg-success';
            case 'pending':
                return 'bg-warning';
            case 'terminated':
                return 'bg-danger';
            case 'draft':
                return 'bg-secondary';
            default:
                return 'bg-secondary';
        }
    }

    public function getStatusText()
    {
        switch ($this->status) {
            case 'active':
                return 'Actif';
            case 'pending':
                return 'En attente';
            case 'terminated':
                return 'Résilié';
            case 'draft':
                return 'Brouillon';
            default:
                return ucfirst($this->status);
        }
    }
}