<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiometricAttendanceController extends Controller
{
    /**
     * Handle biometric check-in/check-out requests from biometric devices
     */
    public function handleBiometricData(Request $request)
    {
        try {
            // Validate the incoming data
            $validated = $request->validate([
                'biometric_id' => 'required|string',
                'device_id' => 'required|string',
                'device_name' => 'nullable|string',
                'timestamp' => 'required|date',
                'type' => 'required|in:fingerprint,face,iris,card,pin',
                'data' => 'nullable|array'
            ]);

            // Find employee by biometric ID
            $employee = Employee::where('biometric_id', $validated['biometric_id'])->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found for biometric ID: ' . $validated['biometric_id']
                ], 404);
            }

            // Check if attendance record already exists for this employee and date
            $date = \Carbon\Carbon::parse($validated['timestamp'])->toDateString();
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $date)
                ->first();

            if (!$attendance) {
                // Create new attendance record for check-in
                $attendance = Attendance::create([
                    'employee_id' => $employee->id,
                    'biometric_id' => $validated['biometric_id'],
                    'device_id' => $validated['device_id'],
                    'device_name' => $validated['device_name'],
                    'biometric_timestamp' => $validated['timestamp'],
                    'biometric_type' => $validated['type'],
                    'biometric_data' => $validated['data'],
                    'date' => $date,
                    'check_in' => \Carbon\Carbon::parse($validated['timestamp'])->toTimeString(),
                    'status' => 'present'
                ]);

                // Calculate late minutes
                $scheduleStart = \Carbon\Carbon::createFromTimeString($employee->schedule_start ?? '08:00:00');
                $checkInTime = \Carbon\Carbon::parse($validated['timestamp']);
                $lateMinutes = $checkInTime->diffInMinutes($scheduleStart, false);
                $attendance->late_minutes = max(0, $lateMinutes);
                $attendance->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in recorded successfully',
                    'attendance' => $attendance
                ]);
            } else {
                // Update existing attendance record for check-out
                if (!$attendance->check_out) {
                    $attendance->update([
                        'check_out' => \Carbon\Carbon::parse($validated['timestamp'])->toTimeString(),
                        'biometric_timestamp' => $validated['timestamp'],
                        'biometric_type' => $validated['type'],
                        'biometric_data' => $validated['data']
                    ]);

                    // Calculate overtime minutes
                    $scheduleEnd = \Carbon\Carbon::createFromTimeString($employee->schedule_end ?? '17:00:00');
                    $checkOutTime = \Carbon\Carbon::parse($validated['timestamp']);
                    $overtimeMinutes = $checkOutTime->diffInMinutes($scheduleEnd, false);
                    $attendance->overtime_minutes = max(0, $overtimeMinutes);
                    $attendance->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Check-out recorded successfully',
                        'attendance' => $attendance
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Check-out already recorded for this employee today'
                    ], 400);
                }
            }
        } catch (\Exception $e) {
            Log::error('Biometric attendance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing biometric data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync employee biometric data to biometric devices
     */
    public function syncEmployees()
    {
        try {
            $employees = Employee::where('status', 'active')
                ->whereNotNull('biometric_id')
                ->get(['id', 'biometric_id', 'first_name', 'last_name', 'email']);

            return response()->json([
                'success' => true,
                'employees' => $employees
            ]);
        } catch (\Exception $e) {
            Log::error('Employee sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error syncing employees: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance records for a specific device
     */
    public function getDeviceAttendance(Request $request, $deviceId)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $attendances = Attendance::with('employee')
                ->where('device_id', $deviceId)
                ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
                ->orderBy('date', 'desc')
                ->orderBy('biometric_timestamp', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'attendances' => $attendances
            ]);
        } catch (\Exception $e) {
            Log::error('Device attendance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving attendance data: ' . $e->getMessage()
            ], 500);
        }
    }
}