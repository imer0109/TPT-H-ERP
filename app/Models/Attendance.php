<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'biometric_id',
        'device_id',
        'device_name',
        'biometric_timestamp',
        'biometric_type',
        'biometric_data',
        'date',
        'check_in',
        'check_out',
        'late_minutes',
        'overtime_minutes',
        'status',
        'check_in_photo',
        'check_out_photo',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'biometric_timestamp' => 'datetime',
        'biometric_data' => 'array',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessors
    public function getWorkedHoursAttribute()
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = \Carbon\Carbon::parse($this->check_in);
            $checkOut = \Carbon\Carbon::parse($this->check_out);
            return $checkOut->diffInHours($checkIn);
        }
        return 0;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'present' => 'success',
            'absent' => 'danger', 
            'half_day' => 'warning',
            'late' => 'info',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'present' => 'Présent',
            'absent' => 'Absent',
            'half_day' => 'Demi-journée',
            'late' => 'En retard',
            default => 'Inconnu'
        };
    }

    // Methods
    public function isLate()
    {
        return $this->late_minutes > 0;
    }

    public function hasOvertime()
    {
        return $this->overtime_minutes > 0;
    }

    public function getTotalWorkedTime()
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = \Carbon\Carbon::parse($this->check_in);
            $checkOut = \Carbon\Carbon::parse($this->check_out);
            return $checkOut->diff($checkIn)->format('%H:%I');
        }
        return '00:00';
    }

    public function isBiometric()
    {
        return !is_null($this->biometric_id);
    }

    public function getBiometricTypeNameAttribute()
    {
        $types = [
            'fingerprint' => 'Empreinte digitale',
            'face' => 'Reconnaissance faciale',
            'iris' => 'Reconnaissance de l\'iris', 
            'card' => 'Carte',
            'pin' => 'Code PIN'
        ];

        return $types[$this->biometric_type] ?? 'Inconnu';
    }
}