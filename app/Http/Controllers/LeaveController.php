<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Http\Requests\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            ->orderBy('date_debut', 'desc')
            ->paginate(15);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all()->map(function($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->matricule . ' - ' . $employee->nom . ' ' . $employee->prenom
            ];
        })->pluck('name', 'id');

        $leaveTypes = LeaveType::pluck('name', 'id');

        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(LeaveRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('justificatif')) {
            $data['justificatif'] = $request->file('justificatif')
                ->store('leaves/documents', 'public');
        }

        $data['created_by'] = Auth::id();
        $data['status'] = 'pending';

        Leave::create($data);

        return redirect()
            ->route('leaves.index')
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

        $leave->update([
            'status' => 'approved',
            'validated_by' => Auth::id(),
            'date_validation' => now()
        ]);

        return redirect()
            ->route('leaves.show', $leave)
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
            ->route('leaves.show', $leave)
            ->with('success', 'Demande de congé rejetée');
    }

    public function cancel(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Seules les demandes en attente peuvent être annulées');
        }

        $leave->update(['status' => 'cancelled']);

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Demande de congé annulée');
    }
}