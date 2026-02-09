<?php

namespace App\Http\Controllers;

use App\Models\PayrollItem;
use Illuminate\Http\Request;

class PayrollItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrollItems = PayrollItem::ordered()->get();
        return view('payroll-items.index', compact('payrollItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:earning,deduction',
            'calculation_type' => 'required|in:fixed,percentage,formula',
            'calculation_value' => 'required|string',
            'is_taxable' => 'boolean',
            'affects_gross' => 'boolean',
            'is_mandatory' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        PayrollItem::create($validated);

        return redirect()->route('hr.payroll-items.index')
            ->with('success', 'Élément de paie créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PayrollItem $payrollItem)
    {
        return view('payroll-items.show', compact('payrollItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollItem $payrollItem)
    {
        return view('payroll-items.edit', compact('payrollItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayrollItem $payrollItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:earning,deduction',
            'calculation_type' => 'required|in:fixed,percentage,formula',
            'calculation_value' => 'required|string',
            'is_taxable' => 'boolean',
            'affects_gross' => 'boolean',
            'is_mandatory' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        $payrollItem->update($validated);

        return redirect()->route('hr.payroll-items.index')
            ->with('success', 'Élément de paie mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayrollItem $payrollItem)
    {
        $payrollItem->delete();

        return redirect()->route('hr.payroll-items.index')
            ->with('success', 'Élément de paie supprimé avec succès.');
    }
}