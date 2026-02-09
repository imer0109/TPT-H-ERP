<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Agency;
use App\Models\Team;
use App\Models\Department;
use Illuminate\Http\Request;

class UserAssignmentController extends Controller
{
    public function index()
    {
        $users = User::with(['societes', 'agences', 'team', 'department'])->paginate(10);
        return view('user_assignments.index', compact('users'));
    }
    
    public function showAssignments(User $user)
    {
        $user->load(['societes', 'agences', 'team', 'department', 'manager']);
        $companies = Company::all();
        $agencies = Agency::all();
        $teams = Team::all();
        $departments = Department::all();
        $managers = User::where('id', '!=', $user->id)->get();
        
        return view('user_assignments.assign', compact('user', 'companies', 'agencies', 'teams', 'departments', 'managers'));
    }
    
    public function assignToCompany(Request $request, User $user)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut'
        ]);
        
        $user->assignToCompany(
            $validated['company_id'], 
            $validated['date_debut'] ?? null, 
            $validated['date_fin'] ?? null
        );
        
        return redirect()->back()
            ->with('success', 'Utilisateur affecté à la société avec succès.');
    }
    
    public function assignToAgency(Request $request, User $user)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut'
        ]);
        
        $user->assignToAgency(
            $validated['agency_id'], 
            $validated['date_debut'] ?? null, 
            $validated['date_fin'] ?? null
        );
        
        return redirect()->back()
            ->with('success', 'Utilisateur affecté à l\'agence avec succès.');
    }
    
    public function assignToTeam(Request $request, User $user)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id'
        ]);
        
        $user->update(['team_id' => $validated['team_id']]);
        
        return redirect()->back()
            ->with('success', 'Utilisateur affecté à l\'équipe avec succès.');
    }
    
    public function assignToDepartment(Request $request, User $user)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);
        
        $user->update(['department_id' => $validated['department_id']]);
        
        return redirect()->back()
            ->with('success', 'Utilisateur affecté au département avec succès.');
    }
    
    public function assignManager(Request $request, User $user)
    {
        $validated = $request->validate([
            'manager_id' => 'required|exists:users,id'
        ]);
        
        $user->update(['manager_id' => $validated['manager_id']]);
        
        return redirect()->back()
            ->with('success', 'Responsable hiérarchique assigné avec succès.');
    }
    
    public function removeCompanyAssignment(User $user, $companyId)
    {
        $user->removeFromCompany($companyId);
        
        return redirect()->back()
            ->with('success', 'Affectation à la société supprimée.');
    }
    
    public function removeAgencyAssignment(User $user, $agencyId)
    {
        $user->removeFromAgency($agencyId);
        
        return redirect()->back()
            ->with('success', 'Affectation à l\'agence supprimée.');
    }
}