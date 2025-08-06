<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'birth_place',
        'photo',
        'nationality',
        'cnss_number',
        'id_card_number',
        'nui_number',
        'email',
        'phone',
        'address',
        'current_company_id',
        'current_agency_id',
        'current_warehouse_id',
        'current_position_id',
        'supervisor_id',
        'status'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relations
    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    public function currentAgency()
    {
        return $this->belongsTo(Agency::class, 'current_agency_id');
    }

    public function currentWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'current_warehouse_id');
    }

    public function currentPosition()
    {
        return $this->belongsTo(Position::class, 'current_position_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function currentContract()
    {
        return $this->hasOne(Contract::class)
            ->where('end_date', '>', now())
            ->orWhereNull('end_date')
            ->orderByDesc('start_date');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    // Accesseurs
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Méthodes
    public function calculateLeaveBalance($leaveTypeId)
    {
        // Logique de calcul du solde de congés
    }

    public function isOnLeave()
    {
        return $this->leaves()
            ->where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
    }

    public function generatePayslip($periodStart, $periodEnd)
    {
        // Logique de génération de bulletin de paie
    }
}