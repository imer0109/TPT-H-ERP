<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        $query = Payslip::with(['employee', 'company']);
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        
        $payslips = $query->orderBy('created_at', 'desc')->paginate(15);
        $employees = Employee::orderBy('last_name')->orderBy('first_name')->get();
        
        return view('payslips.index', compact('payslips', 'employees'));
    }
    
    public function create()
    {
        $employees = Employee::orderBy('last_name')->orderBy('first_name')->get();
        $companies = \App\Models\Company::orderBy('raison_sociale')->get();
        
        return view('payslips.create', compact('employees', 'companies'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'performance_bonus' => 'nullable|numeric|min:0',
            'social_security' => 'nullable|numeric|min:0',
            'income_tax' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'allowances_description' => 'nullable|string|max:255',
            'advance_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'deductions_description' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Prepare earnings array
        $earnings = [];
        if ($validated['overtime_hours'] > 0 && $validated['overtime_rate'] > 0) {
            $earnings['overtime'] = $validated['overtime_hours'] * $validated['overtime_rate'];
        }
        if ($validated['transport_allowance'] > 0) {
            $earnings['transport'] = $validated['transport_allowance'];
        }
        if ($validated['housing_allowance'] > 0) {
            $earnings['housing'] = $validated['housing_allowance'];
        }
        if ($validated['meal_allowance'] > 0) {
            $earnings['meal'] = $validated['meal_allowance'];
        }
        if ($validated['performance_bonus'] > 0) {
            $earnings['performance_bonus'] = $validated['performance_bonus'];
        }
        if ($validated['other_allowances'] > 0) {
            $earnings['other'] = $validated['other_allowances'];
        }
        
        // Prepare deductions array
        $deductions = [];
        if ($validated['social_security'] > 0) {
            $deductions['social_security'] = $validated['social_security'];
        }
        if ($validated['income_tax'] > 0) {
            $deductions['income_tax'] = $validated['income_tax'];
        }
        if ($validated['advance_deduction'] > 0) {
            $deductions['advance'] = $validated['advance_deduction'];
        }
        if ($validated['loan_deduction'] > 0) {
            $deductions['loan'] = $validated['loan_deduction'];
        }
        if ($validated['other_deductions'] > 0) {
            $deductions['other'] = $validated['other_deductions'];
        }
        
        // Calculate totals
        $grossSalary = $validated['base_salary'] + array_sum($earnings);
        $totalDeductions = array_sum($deductions);
        $netSalary = $grossSalary - $totalDeductions;
        
        $payslip = Payslip::create([
            'reference' => $this->generateReference(),
            'company_id' => $validated['company_id'],
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'base_salary' => $validated['base_salary'],
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'overtime_hours' => $validated['overtime_hours'] ?? 0,
            'overtime_rate' => $validated['overtime_rate'] ?? 0,
            'transport_allowance' => $validated['transport_allowance'] ?? 0,
            'housing_allowance' => $validated['housing_allowance'] ?? 0,
            'meal_allowance' => $validated['meal_allowance'] ?? 0,
            'performance_bonus' => $validated['performance_bonus'] ?? 0,
            'social_security' => $validated['social_security'] ?? 0,
            'income_tax' => $validated['income_tax'] ?? 0,
            'total_deductions' => $totalDeductions,
            'other_allowances' => $validated['other_allowances'] ?? 0,
            'allowances_description' => $validated['allowances_description'] ?? null,
            'advance_deduction' => $validated['advance_deduction'] ?? 0,
            'loan_deduction' => $validated['loan_deduction'] ?? 0,
            'other_deductions' => $validated['other_deductions'] ?? 0,
            'deductions_description' => $validated['deductions_description'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'payment_method' => $validated['payment_method'],
            'status' => 'draft',
            'generated_by' => Auth::id()
        ]);
        
        return redirect()->route('hr.payslips.show', $payslip)
            ->with('success', 'Fiche de paie créée avec succès');
    }
    
    public function show(Payslip $payslip)
    {
        $payslip->load(['employee', 'company', 'generator']);
        return view('payslips.show', compact('payslip'));
    }
    
    public function edit(Payslip $payslip)
    {
        if ($payslip->status !== 'draft') {
            return back()->with('error', 'Seules les fiches de paie en brouillon peuvent être modifiées.');
        }
        
        $employees = Employee::orderBy('last_name')->orderBy('first_name')->get();
        $companies = \App\Models\Company::orderBy('raison_sociale')->get();
        
        return view('payslips.edit', compact('payslip', 'employees', 'companies'));
    }
    
    public function update(Request $request, Payslip $payslip)
    {
        if ($payslip->status !== 'draft') {
            return back()->with('error', 'Seules les fiches de paie en brouillon peuvent être modifiées.');
        }
        
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'performance_bonus' => 'nullable|numeric|min:0',
            'social_security' => 'nullable|numeric|min:0',
            'income_tax' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'allowances_description' => 'nullable|string|max:255',
            'advance_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'deductions_description' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Prepare earnings array
        $earnings = [];
        if ($validated['overtime_hours'] > 0 && $validated['overtime_rate'] > 0) {
            $earnings['overtime'] = $validated['overtime_hours'] * $validated['overtime_rate'];
        }
        if ($validated['transport_allowance'] > 0) {
            $earnings['transport'] = $validated['transport_allowance'];
        }
        if ($validated['housing_allowance'] > 0) {
            $earnings['housing'] = $validated['housing_allowance'];
        }
        if ($validated['meal_allowance'] > 0) {
            $earnings['meal'] = $validated['meal_allowance'];
        }
        if ($validated['performance_bonus'] > 0) {
            $earnings['performance_bonus'] = $validated['performance_bonus'];
        }
        if ($validated['other_allowances'] > 0) {
            $earnings['other'] = $validated['other_allowances'];
        }
        
        // Prepare deductions array
        $deductions = [];
        if ($validated['social_security'] > 0) {
            $deductions['social_security'] = $validated['social_security'];
        }
        if ($validated['income_tax'] > 0) {
            $deductions['income_tax'] = $validated['income_tax'];
        }
        if ($validated['advance_deduction'] > 0) {
            $deductions['advance'] = $validated['advance_deduction'];
        }
        if ($validated['loan_deduction'] > 0) {
            $deductions['loan'] = $validated['loan_deduction'];
        }
        if ($validated['other_deductions'] > 0) {
            $deductions['other'] = $validated['other_deductions'];
        }
        
        // Calculate totals
        $grossSalary = $validated['base_salary'] + array_sum($earnings);
        $totalDeductions = array_sum($deductions);
        $netSalary = $grossSalary - $totalDeductions;
        
        $payslip->update([
            'company_id' => $validated['company_id'],
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'base_salary' => $validated['base_salary'],
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'overtime_hours' => $validated['overtime_hours'] ?? 0,
            'overtime_rate' => $validated['overtime_rate'] ?? 0,
            'transport_allowance' => $validated['transport_allowance'] ?? 0,
            'housing_allowance' => $validated['housing_allowance'] ?? 0,
            'meal_allowance' => $validated['meal_allowance'] ?? 0,
            'performance_bonus' => $validated['performance_bonus'] ?? 0,
            'social_security' => $validated['social_security'] ?? 0,
            'income_tax' => $validated['income_tax'] ?? 0,
            'total_deductions' => $totalDeductions,
            'other_allowances' => $validated['other_allowances'] ?? 0,
            'allowances_description' => $validated['allowances_description'] ?? null,
            'advance_deduction' => $validated['advance_deduction'] ?? 0,
            'loan_deduction' => $validated['loan_deduction'] ?? 0,
            'other_deductions' => $validated['other_deductions'] ?? 0,
            'deductions_description' => $validated['deductions_description'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'payment_method' => $validated['payment_method']
        ]);
        
        return redirect()->route('hr.payslips.show', $payslip)
            ->with('success', 'Fiche de paie mise à jour avec succès');
    }
    
    public function destroy(Payslip $payslip)
    {
        if ($payslip->status !== 'draft') {
            return back()->with('error', 'Seules les fiches de paie en brouillon peuvent être supprimées.');
        }
        
        $payslip->delete();
        
        return redirect()->route('hr.payslips.index')
            ->with('success', 'Fiche de paie supprimée avec succès');
    }
    
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'employee_ids' => 'required|array'
        ]);
        
        $generated = 0;
        foreach ($validated['employee_ids'] as $employeeId) {
            $employee = Employee::find($employeeId);
            if ($employee) {
                $this->generatePayslipForEmployee($employee, $request);
                $generated++;
            }
        }
        
        return back()->with('success', "{$generated} fiche(s) de paie générée(s) avec succès");
    }
    
    public function validatePayslip(Payslip $payslip)
    {
        if ($payslip->status !== 'draft') {
            return back()->with('error', 'Seules les fiches de paie en brouillon peuvent être validées.');
        }
        
        $payslip->update([
            'status' => 'validated',
            'validated_by' => Auth::id(),
            'validated_at' => now()
        ]);
        
        return back()->with('success', 'Fiche de paie validée avec succès');
    }
    
    public function pay(Payslip $payslip)
    {
        if ($payslip->status !== 'validated') {
            return back()->with('error', 'Seules les fiches de paie validées peuvent être payées.');
        }
        
        $payslip->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
        
        return back()->with('success', 'Paiement enregistré avec succès');
    }
    
    public function download(Payslip $payslip)
    {
        // Generate PDF if not exists
        if (!$payslip->pdf_file || !Storage::disk('public')->exists(str_replace('storage/', '', $payslip->pdf_file))) {
            $this->generatePDF($payslip);
        }
        
        $filePath = storage_path('app/public/' . str_replace('storage/', '', $payslip->pdf_file));
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Le fichier PDF n\'existe pas.');
        }
        
        return Response::download($filePath, $payslip->reference . '.pdf');
    }
    
    private function generateReference()
    {
        $date = now()->format('Ym');
        $count = Payslip::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
        
        return sprintf('PAY-%s-%04d', $date, $count);
    }
    
    private function generatePayslipForEmployee($employee, $request)
    {
        // This would contain logic to calculate salary based on employee contract
        // For now, using basic salary calculation
        $baseSalary = $employee->salaire_base ?? 500000; // Default base salary
        
        $earnings = [];
        $deductions = [];
        
        // Calculate gross and net salary
        $grossSalary = $baseSalary + array_sum($earnings);
        $netSalary = $grossSalary - array_sum($deductions);
        
        return Payslip::create([
            'reference' => $this->generateReference(),
            'employee_id' => $employee->id,
            'company_id' => $request->company_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'base_salary' => $baseSalary,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'payment_method' => 'bank_transfer',
            'status' => 'draft',
            'generated_by' => Auth::id()
        ]);
    }
    
    private function generatePDF($payslip)
    {
        // Create a simple HTML content for the payslip
        $html = view('payslips.pdf', compact('payslip'))->render();
        
        // For now, we'll create a simple text file as a placeholder
        // In a real application, you would use a library like DomPDF or TCPDF
        $fileName = 'payslips/' . $payslip->reference . '.pdf';
        $filePath = storage_path('app/public/' . $fileName);
        
        // Create a simple placeholder PDF content
        $pdfContent = "FICHE DE PAIE\n\n";
        $pdfContent .= "Référence: " . $payslip->reference . "\n";
        $pdfContent .= "Employé: " . $payslip->employee->prenom . " " . $payslip->employee->nom . "\n";
        $pdfContent .= "Période: " . $payslip->period_start->format('d/m/Y') . " - " . $payslip->period_end->format('d/m/Y') . "\n\n";
        $pdfContent .= "Salaire de Base: " . number_format($payslip->base_salary, 0, ',', ' ') . " FCFA\n";
        $pdfContent .= "Salaire Brut: " . number_format($payslip->gross_salary, 0, ',', ' ') . " FCFA\n";
        $pdfContent .= "Total Déductions: " . number_format($payslip->total_deductions, 0, ',', ' ') . " FCFA\n";
        $pdfContent .= "Salaire Net: " . number_format($payslip->net_salary, 0, ',', ' ') . " FCFA\n";
        
        // Save the file
        file_put_contents($filePath, $pdfContent);
        
        // Update the payslip with the PDF file path
        $payslip->update([
            'pdf_file' => $fileName
        ]);
        
        return $fileName;
    }
}