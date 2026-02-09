<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with(['employee.currentPosition'])
            ->when($request->date, function($query, $date) {
                $query->whereDate('date', $date);
            })
            ->when($request->employee_id, function($query, $employee_id) {
                $query->where('employee_id', $employee_id);
            })
            ->when($request->position, function($query, $position) {
                $query->whereHas('employee', function($q) use ($position) {
                    $q->where('current_position_id', $position);
                });
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(15);

        // Get unique departments from positions for filtering
        $departments = Position::whereNotNull('title')
            ->select('id', 'title as name')
            ->distinct()
            ->get();

        return view('attendances.index', compact('attendances', 'departments'));
    }

    public function create()
    {
        $employees = Employee::with('currentPosition')
            ->where('status', 'active')
            ->get();

        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,absent,late,half_day',
            'late_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
            'check_in_photo' => 'nullable|image|max:2048'
        ]);

        // Check if attendance already exists for this employee and date
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Un pointage existe déjà pour cet employé à cette date.');
        }

        $data = $request->only([
            'employee_id', 'date', 'check_in', 'check_out', 
            'status', 'late_minutes', 'notes'
        ]);

        // Handle photo upload
        if ($request->hasFile('check_in_photo')) {
            $data['check_in_photo'] = $request->file('check_in_photo')
                ->store('attendances/photos', 'public');
        }

        // Set defaults
        $data['late_minutes'] = $data['late_minutes'] ?? 0;
        $data['overtime_minutes'] = 0;

        Attendance::create($data);

        return redirect()
            ->route('hr.attendances.index')
            ->with('success', 'Pointage créé avec succès');
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'photo' => 'required|image|max:2048'
        ]);

        $now = now();
        $photo = $request->file('photo')->store('attendances/photos', 'public');

        // Vérifier si l'employé n'a pas déjà pointé aujourd'hui
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if ($existingAttendance) {
            Storage::disk('public')->delete($photo);
            return back()->with('error', 'Vous avez déjà pointé aujourd\'hui');
        }

        // Calculer les minutes de retard
        $employee = Employee::find($request->employee_id);
        $scheduleStart = Carbon::createFromTimeString($employee->schedule_start);
        $lateMinutes = $now->diffInMinutes($scheduleStart, false);

        Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => $now->toDateString(),
            'check_in' => $now->toTimeString(),
            'check_in_photo' => $photo,
            'late_minutes' => max(0, $lateMinutes),
            'status' => 'present'
        ]);

        return redirect()
            ->route('hr.attendances.index')
            ->with('success', 'Pointage d\'entrée enregistré');
    }

    public function checkOut(Request $request, Attendance $attendance)
    {
        $request->validate([
            'photo' => 'required|image|max:2048'
        ]);

        if ($attendance->check_out) {
            return back()->with('error', 'Le pointage de sortie a déjà été enregistré');
        }

        $now = now();
        $photo = $request->file('photo')->store('attendances/photos', 'public');

        // Calculer les minutes supplémentaires
        $employee = $attendance->employee;
        $scheduleEnd = Carbon::createFromTimeString($employee->schedule_end);
        $overtimeMinutes = $now->diffInMinutes($scheduleEnd, false);

        $attendance->update([
            'check_out' => $now->toTimeString(),
            'check_out_photo' => $photo,
            'overtime_minutes' => max(0, $overtimeMinutes)
        ]);

        return redirect()
            ->route('hr.attendances.index')
            ->with('success', 'Pointage de sortie enregistré');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('employee');
        return view('attendances.show', compact('attendance'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:employees,id'
        ]);

        $attendances = Attendance::with('employee')
            ->whereBetween('date', [
                $request->start_date,
                $request->end_date
            ])
            ->when($request->employee_id, function($query, $employee_id) {
                $query->where('employee_id', $employee_id);
            })
            ->orderBy('date')
            ->get()
            ->groupBy('employee_id');

        return view('attendances.report', compact('attendances'));
    }
    
    /**
     * Show biometric attendance management page
     */
    public function biometric()
    {
        // Get unique biometric devices
        $devices = Attendance::select('device_id', 'device_name')
            ->whereNotNull('device_id')
            ->groupBy('device_id', 'device_name')
            ->get();
            
        // Get recent biometric attendance records
        $biometricAttendances = Attendance::with('employee')
            ->whereNotNull('biometric_id')
            ->orderBy('biometric_timestamp', 'desc')
            ->limit(20)
            ->get();

        return view('attendances.biometric', compact('devices', 'biometricAttendances'));
    }
    
    /**
     * Sync employees with biometric devices
     */
    public function syncEmployees()
    {
        try {
            $employees = Employee::where('status', 'active')
                ->whereNotNull('biometric_id')
                ->get(['id', 'biometric_id', 'first_name', 'last_name', 'email']);

            return response()->json([
                'success' => true,
                'employees' => $employees,
                'count' => $employees->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error syncing employees: ' . $e->getMessage()
            ], 500);
        }
    }
}
