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
        'nom', // French field name for last_name
        'prenom', // French field name for first_name
        'matricule', // Employee ID/number
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
        'telephone', // Alternative field name for phone
        'address',
        'current_company_id',
        'company_id', // Alternative field name
        'current_agency_id',
        'agency_id', // Alternative field name
        'current_warehouse_id',
        'current_position_id',
        'position_id', // Alternative field name
        'supervisor_id',
        'salaire_base', // Base salary
        'date_embauche', // Hire date
        'status',
        'biometric_id', // Biometric ID for attendance tracking
        'schedule_start', // Work schedule start time
        'schedule_end', // Work schedule end time
        'user_id' // Link to user account
    ];

    protected $casts = [
        'birth_date' => 'date',
        'date_embauche' => 'date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
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

    public function getDepartmentAttribute()
    {
        // Return a mock department object for compatibility
        return (object) ['nom' => $this->currentPosition->title ?? 'N/A'];
    }

    public function getMatriculeAttribute()
    {
        // Generate matricule from ID if not set
        return $this->attributes['matricule'] ?? 'EMP' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getDateEmbaucheAttribute()
    {
        return $this->date_embauche ?? $this->created_at;
    }

    public function getSalaireBaseAttribute()
    {
        return $this->attributes['salaire_base'] ?? 0;
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
    
    public function assignments()
    {
        return $this->hasMany(EmployeeAssignment::class);
    }
    
    public function currentAssignments()
    {
        return $this->hasMany(EmployeeAssignment::class)->active();
    }
    
    public function primaryAssignment()
    {
        return $this->hasOne(EmployeeAssignment::class)->primary();
    }

    // Accesseurs
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // French field name accessors for compatibility
    public function getPrenomAttribute()
    {
        return $this->first_name;
    }

    public function getNomAttribute()
    {
        return $this->last_name;
    }

    public function getTelephoneAttribute()
    {
        return $this->phone;
    }

    public function getCompanyAttribute()
    {
        return $this->currentCompany;
    }

    public function getAgencyAttribute()
    {
        return $this->currentAgency;
    }

    public function getPositionAttribute()
    {
        return $this->currentPosition;
    }

    // Mutators for French field names
    public function setPrenomAttribute($value)
    {
        $this->attributes['first_name'] = $value;
    }

    public function setNomAttribute($value)
    {
        $this->attributes['last_name'] = $value;
    }

    public function setTelephoneAttribute($value)
    {
        $this->attributes['phone'] = $value;
    }

    // Méthodes
    public function calculateLeaveBalance($leaveTypeId)
    {
        // Get the leave balance record for this employee and leave type
        $leaveBalance = $this->leaveBalances()
            ->forLeaveType($leaveTypeId)
            ->active()
            ->first();
            
        if (!$leaveBalance) {
            // If no balance record exists, create a default one based on leave type
            $leaveType = LeaveType::find($leaveTypeId);
            if ($leaveType) {
                $leaveBalance = LeaveBalance::create([
                    'employee_id' => $this->id,
                    'leave_type_id' => $leaveTypeId,
                    'total_allocated' => $leaveType->default_days,
                    'balance' => $leaveType->default_days,
                    'effective_date' => now()->startOfYear(),
                    'expiry_date' => now()->endOfYear()
                ]);
            }
        }
        
        return $leaveBalance ? $leaveBalance->balance : 0;
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
    
    // Add the leaveBalances relationship
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
    
    // Get available leave balance for a specific leave type
    public function getLeaveBalance($leaveTypeId)
    {
        return $this->calculateLeaveBalance($leaveTypeId);
    }
    
    // Check if employee has enough leave balance for a request
    public function hasEnoughLeaveBalance($leaveTypeId, $days)
    {
        $balance = $this->calculateLeaveBalance($leaveTypeId);
        return $balance >= $days;
    }
}