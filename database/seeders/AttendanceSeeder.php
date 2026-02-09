<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::take(5)->get(); // Get first 5 employees
        
        if ($employees->isEmpty()) {
            // Create a sample employee if none exist
            $employee = Employee::create([
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'gender' => 'M',
                'birth_date' => '1990-01-01',
                'birth_place' => 'Paris',
                'nationality' => 'Française',
                'id_card_number' => 'ID123456',
                'email' => 'jean.dupont@company.com',
                'phone' => '+33123456789',
                'address' => '123 Rue de la Paix, Paris',
                'current_company_id' => 1,
                'current_agency_id' => 1,
                'current_position_id' => 1,
                'status' => 'active'
            ]);
            $employees = collect([$employee]);
        }

        // Generate attendance data for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($employees as $employee) {
                // 90% chance of attendance
                if (rand(1, 100) <= 90) {
                    $checkIn = $date->copy()->setTime(8, rand(0, 30), 0); // Between 8:00-8:30
                    $checkOut = $date->copy()->setTime(17, rand(0, 60), 0); // Between 17:00-18:00
                    
                    $lateMinutes = max(0, $checkIn->diffInMinutes($date->copy()->setTime(8, 0, 0), false));
                    $overtimeMinutes = max(0, $checkOut->diffInMinutes($date->copy()->setTime(17, 0, 0), false));

                    $status = 'present';
                    if ($lateMinutes > 0) {
                        $status = 'late';
                    }

                    Attendance::create([
                        'employee_id' => $employee->id,
                        'date' => $date->toDateString(),
                        'check_in' => $checkIn->toTimeString(),
                        'check_out' => $checkOut->toTimeString(),
                        'late_minutes' => $lateMinutes,
                        'overtime_minutes' => $overtimeMinutes,
                        'status' => $status,
                        'notes' => rand(1, 100) <= 10 ? 'Remarque automatique générée' : null
                    ]);
                } else {
                    // Absent
                    Attendance::create([
                        'employee_id' => $employee->id,
                        'date' => $date->toDateString(),
                        'status' => 'absent',
                        'notes' => 'Absence non justifiée'
                    ]);
                }
            }
        }
    }
}
