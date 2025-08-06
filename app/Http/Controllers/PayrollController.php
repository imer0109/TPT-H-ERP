<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\PayrollItem;
use Illuminate\Http\Request;
use App\Http\Requests\PayrollRequest;
use Illuminate\Support\Facades\DB;
use PDF;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payslip::with(['employee'])->latest();

        // Filtres
        if ($request->filled('period')) {
            $query->whereYear('period', substr($request->period, 0, 4))
                  ->whereMonth('period', substr($request->period, 5, 2));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        $payslips = $query->paginate(15);
        $employees = Employee::orderBy('last_name')->get();

        return view('payroll.index', compact('payslips', 'employees'));
    }

    public function create()
    {
        $employees = Employee::with('contract')->orderBy('last_name')->get();
        $payrollItems = PayrollItem::orderBy('name')->get();

        return view('payroll.create', compact('employees', 'payrollItems'));
    }

    public function store(PayrollRequest $request)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::findOrFail($request->employee_id);
            
            // Vérification si une fiche de paie existe déjà pour cette période
            $existingPayslip = Payslip::where('employee_id', $request->employee_id)
                                     ->whereYear('period', substr($request->period, 0, 4))
                                     ->whereMonth('period', substr($request->period, 5, 2))
                                     ->first();

            if ($existingPayslip) {
                throw new \Exception('Une fiche de paie existe déjà pour cet employé sur cette période.');
            }

            $payslip = new Payslip();
            $payslip->employee_id = $request->employee_id;
            $payslip->period = $request->period . '-01';
            $payslip->base_salary = $request->base_salary;
            $payslip->worked_days = $request->worked_days;
            $payslip->overtime_hours = $request->overtime_hours;
            $payslip->gross_salary = $request->gross_salary;
            $payslip->net_salary = $request->net_salary;
            $payslip->status = 'draft';
            $payslip->reference = $this->generateReference($request->employee_id, $request->period);
            
            // Sauvegarde des gains et déductions
            $earnings = [];
            foreach ($request->earnings as $itemId => $amount) {
                $item = PayrollItem::find($itemId);
                if ($item && $amount > 0) {
                    $earnings[] = [
                        'name' => $item->name,
                        'amount' => $amount,
                        'calculation_type' => $item->calculation_type
                    ];
                }
            }
            $payslip->earnings = $earnings;

            $deductions = [];
            foreach ($request->deductions as $itemId => $amount) {
                $item = PayrollItem::find($itemId);
                if ($item && $amount > 0) {
                    $deductions[] = [
                        'name' => $item->name,
                        'amount' => $amount,
                        'calculation_type' => $item->calculation_type
                    ];
                }
            }
            $payslip->deductions = $deductions;

            $payslip->save();

            DB::commit();

            return redirect()->route('payroll.show', $payslip)
                            ->with('success', 'La fiche de paie a été créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erreur lors de la création de la fiche de paie : ' . $e->getMessage());
        }
    }

    public function show(Payslip $payslip)
    {
        $payslip->load('employee.position');
        return view('payroll.show', compact('payslip'));
    }

    public function edit(Payslip $payslip)
    {
        if ($payslip->status !== 'draft') {
            return redirect()->route('payroll.show', $payslip)
                            ->with('error', 'Impossible de modifier une fiche de paie validée ou payée.');
        }

        $payslip->load('employee');
        $payrollItems = PayrollItem::orderBy('name')->get();

        return view('payroll.edit', compact('payslip', 'payrollItems'));
    }

    public function update(PayrollRequest $request, Payslip $payslip)
    {
        try {
            if ($payslip->status !== 'draft') {
                throw new \Exception('Impossible de modifier une fiche de paie validée ou payée.');
            }

            DB::beginTransaction();

            $payslip->worked_days = $request->worked_days;
            $payslip->overtime_hours = $request->overtime_hours;
            $payslip->gross_salary = $request->gross_salary;
            $payslip->net_salary = $request->net_salary;

            // Mise à jour des gains et déductions
            $earnings = [];
            foreach ($request->earnings as $itemId => $amount) {
                $item = PayrollItem::find($itemId);
                if ($item && $amount > 0) {
                    $earnings[] = [
                        'name' => $item->name,
                        'amount' => $amount,
                        'calculation_type' => $item->calculation_type
                    ];
                }
            }
            $payslip->earnings = $earnings;

            $deductions = [];
            foreach ($request->deductions as $itemId => $amount) {
                $item = PayrollItem::find($itemId);
                if ($item && $amount > 0) {
                    $deductions[] = [
                        'name' => $item->name,
                        'amount' => $amount,
                        'calculation_type' => $item->calculation_type
                    ];
                }
            }
            $payslip->deductions = $deductions;

            $payslip->save();

            DB::commit();

            return redirect()->route('payroll.show', $payslip)
                            ->with('success', 'La fiche de paie a été mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erreur lors de la mise à jour de la fiche de paie : ' . $e->getMessage());
        }
    }

    public function validate(Payslip $payslip)
    {
        try {
            if ($payslip->status !== 'draft') {
                throw new \Exception('Cette fiche de paie ne peut pas être validée.');
            }

            $payslip->status = 'validated';
            $payslip->validated_at = now();
            $payslip->save();

            // Génération du PDF
            $this->generatePDF($payslip);

            return response()->json(['message' => 'La fiche de paie a été validée avec succès.']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function pay(Payslip $payslip)
    {
        try {
            if ($payslip->status !== 'validated') {
                throw new \Exception('Cette fiche de paie ne peut pas être marquée comme payée.');
            }

            $payslip->status = 'paid';
            $payslip->paid_at = now();
            $payslip->save();

            return response()->json(['message' => 'La fiche de paie a été marquée comme payée.']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function pdf(Payslip $payslip)
    {
        if (!$payslip->pdf_path || !file_exists(storage_path('app/' . $payslip->pdf_path))) {
            $this->generatePDF($payslip);
        }

        return response()->file(storage_path('app/' . $payslip->pdf_path));
    }

    private function generateReference($employeeId, $period)
    {
        $employee = Employee::find($employeeId);
        $year = substr($period, 0, 4);
        $month = substr($period, 5, 2);
        
        return sprintf('PAY-%s-%s-%s-%04d',
            $year,
            $month,
            $employee->employee_id,
            Payslip::whereYear('period', $year)->count() + 1
        );
    }

    private function generatePDF(Payslip $payslip)
    {
        $pdf = PDF::loadView('payroll.pdf', compact('payslip'));
        
        $filename = sprintf('payslips/%s/%s/%s.pdf',
            $payslip->period->format('Y'),
            $payslip->period->format('m'),
            $payslip->reference
        );

        Storage::put($filename, $pdf->output());
        
        $payslip->pdf_path = $filename;
        $payslip->save();
    }
}