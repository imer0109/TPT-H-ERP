<?php

namespace App\Http\Controllers;

use App\Models\EntityAuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $query = EntityAuditTrail::with(['user']);
        
        // Apply entity type filter
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->get('entity_type'));
        }
        
        // Apply action filter
        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }
        
        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->get('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->get('end_date') . ' 23:59:59');
        }
        
        // Apply user filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }
        
        $auditTrails = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->except('page'));
        
        // Get filter options
        $users = \App\Models\User::all();
        $entityTypes = ['company', 'agency'];
        $actions = ['created', 'updated', 'deleted', 'archived', 'reactivated', 'duplicated'];
        
        return view('audit-trails.index', compact('auditTrails', 'users', 'entityTypes', 'actions'));
    }
    
    public function showCompanyTrails($id)
    {
        $company = \App\Models\Company::findOrFail($id);
        $auditTrails = $company->auditTrails()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('audit-trails.company', compact('company', 'auditTrails'));
    }
    
    public function showAgencyTrails($id)
    {
        $agency = \App\Models\Agency::findOrFail($id);
        $auditTrails = $agency->auditTrails()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('audit-trails.agency', compact('agency', 'auditTrails'));
    }
}