<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Http\Requests\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaves = Leave::with(['employee', 'leaveType'])
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->employee_id, function($query, $employee_id) {
                $query->where('employee_id', $employee_id);
            })
            ->when($request->type_id, function($query, $type_id) {
                $query->where('leave_type_id', $type_id);
            })
            ->when($request->date_debut, function($query, $date_debut) {
                $query->where('start_date', '>=', $date_debut);
            })
            ->when($request->date_fin, function($query, $date_fin) {
                $query->where('end_date', '<=', $date_fin);
            })
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all()->mapWithKeys(function($employee) {
            return [
                $employee->id => 'EMP' . str_pad($employee->id, 4, '0', STR_PAD_LEFT) . ' - ' . $employee->first_name . ' ' . $employee->last_name
            ];
        });

        $leaveTypes = LeaveType::all();

        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(LeaveRequest $request)
    {
        $data = $request->validated();
        
        // Set employee_id automatically if not provided (for self-service)
        if (!isset($data['employee_id']) || !$data['employee_id']) {
            // Get the authenticated user's employee record
            $userEmployee = Auth::user()->employee;
            if ($userEmployee) {
                $data['employee_id'] = $userEmployee->id;
            } else {
                return back()
                    ->withInput()
                    ->withErrors(['employee_id' => 'Impossible de déterminer votre profil employé. Veuillez contacter l\'administrateur.']);
            }
        }
        
        // Calculate leave duration
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = \Carbon\Carbon::parse($data['end_date']);
        $duration = $startDate->diffInDays($endDate) + 1;
        
        // Check if employee has enough leave balance
        $employee = Employee::find($data['employee_id']);
        if ($employee && !$employee->hasEnoughLeaveBalance($data['leave_type_id'], $duration)) {
            return back()
                ->withInput()
                ->withErrors(['leave_type_id' => 'Solde de congés insuffisant pour cette demande.']);
        }
        
        if ($request->hasFile('supporting_document')) {
            $data['supporting_document'] = $request->file('supporting_document')
                ->store('leaves/documents', 'public');
        }

        $data['created_by'] = Auth::id();
        $data['status'] = 'pending';

        // Debug: Log the data being saved
        Log::info('Leave data being saved:', $data);
        
        try {
            Leave::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating leave: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de la demande de congé.']);
        }

        return redirect()
            ->route('hr.leaves.index')
            ->with('success', 'Demande de congé créée avec succès');
    }

    public function show(Leave $leave)
    {
        $leave->load(['employee', 'leaveType', 'creator', 'validator']);
        return view('leaves.show', compact('leave'));
    }

    public function approve(Leave $leave)
    {
        if (!$leave->canBeApproved()) {
            return back()->with('error', 'Cette demande ne peut pas être approuvée');
        }
        
        // Calculate leave duration
        $startDate = $leave->start_date;
        $endDate = $leave->end_date;
        $duration = $startDate->diffInDays($endDate) + 1;
        
        // Check if employee has enough leave balance
        $employee = $leave->employee;
        if (!$employee->hasEnoughLeaveBalance($leave->leave_type_id, $duration)) {
            return back()->with('error', 'Solde de congés insuffisant pour approuver cette demande.');
        }
        
        // Update leave balance
        $leaveBalance = $employee->leaveBalances()
            ->forLeaveType($leave->leave_type_id)
            ->active()
            ->first();
            
        if ($leaveBalance) {
            $leaveBalance->useDays($duration);
        }

        $leave->update([
            'status' => 'approved',
            'validated_by' => Auth::id(),
            'date_validation' => now()
        ]);

        return redirect()
            ->route('hr.leaves.show', $leave)
            ->with('success', 'Demande de congé approuvée');
    }

    public function reject(Request $request, Leave $leave)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $leave->update([
            'status' => 'rejected',
            'validated_by' => Auth::id(),
            'date_validation' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()
            ->route('hr.leaves.show', $leave)
            ->with('success', 'Demande de congé rejetée');
    }

    public function cancel(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Seules les demandes en attente peuvent être annulées');
        }

        $leave->update(['status' => 'cancelled']);

        return redirect()
            ->route('hr.leaves.index')
            ->with('success', 'Demande de congé annulée');
    }
    
    public function balance(Request $request)
    {
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();
        
        $balances = collect();
        
        if ($request->employee_id) {
            $employee = Employee::find($request->employee_id);
            if ($employee) {
                $leaveTypes->each(function($leaveType) use ($employee, $balances) {
                    $leaveBalance = $employee->leaveBalances()
                        ->forLeaveType($leaveType->id)
                        ->active()
                        ->first();
                        
                    if (!$leaveBalance) {
                        // Create a default balance if none exists
                        $leaveBalance = LeaveBalance::create([
                            'employee_id' => $employee->id,
                            'leave_type_id' => $leaveType->id,
                            'total_allocated' => $leaveType->default_days,
                            'balance' => $leaveType->default_days,
                            'effective_date' => now()->startOfYear(),
                            'expiry_date' => now()->endOfYear()
                        ]);
                    }
                    
                    $balances->push([
                        'employee' => $employee,
                        'leave_type' => $leaveType,
                        'leave_balance' => $leaveBalance,
                        'balance' => $leaveBalance->balance
                    ]);
                });
            }
        }
        
        return view('leaves.balance', compact('employees', 'leaveTypes', 'balances'));
    }
}
