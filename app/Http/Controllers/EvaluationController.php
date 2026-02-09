<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Evaluation::with(['employee', 'evaluator']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('period')) {
            $query->where('period', 'LIKE', "%{$request->period}%");
        }
        
        if ($request->filled('year')) {
            $query->where('period', 'LIKE', "%{$request->year}%");
        }
        
        $evaluations = $query->latest()->paginate(15);
        $employees = Employee::orderBy('last_name')->get();
        
        return view('evaluations.index', compact('evaluations', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::orderBy('last_name')->get();
        $evaluators = Employee::where('status', 'active')->orderBy('last_name')->get();
        $criteria = Evaluation::getDefaultCriteria();
        
        return view('evaluations.create', compact('employees', 'evaluators', 'criteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'evaluator_id' => 'required|exists:employees,id|different:employee_id',
            'evaluation_period' => 'required|string|max:255',
            'evaluation_type' => 'required|string|in:performance,competency,360_feedback,probation,annual_review',
            'due_date' => 'nullable|date',
            'overall_rating' => 'nullable|integer|min:1|max:5',
            'objectives' => 'nullable|string',
            'achievements' => 'nullable|string',
            'areas_improvement' => 'nullable|string',
            'comments' => 'nullable|string',
            'technical_skills' => 'nullable|integer|min:1|max:5',
            'communication_skills' => 'nullable|integer|min:1|max:5',
            'teamwork_skills' => 'nullable|integer|min:1|max:5',
            'leadership_skills' => 'nullable|integer|min:1|max:5',
            'problem_solving' => 'nullable|integer|min:1|max:5',
            'adaptability' => 'nullable|integer|min:1|max:5',
        ]);
        
        // Prepare criteria scores
        $criteriaScores = [];
        $criteriaKeys = ['technical_skills', 'communication_skills', 'teamwork_skills', 
                        'leadership_skills', 'problem_solving', 'adaptability'];
        
        foreach ($criteriaKeys as $key) {
            if ($request->filled($key)) {
                $criteriaScores[$key] = (int) $request->input($key);
            }
        }
        
        // Calculate overall score if not provided
        $overallScore = $validatedData['overall_rating'] ?? null;
        if (!$overallScore && !empty($criteriaScores)) {
            $total = array_sum($criteriaScores);
            $overallScore = count($criteriaScores) > 0 ? round($total / count($criteriaScores), 2) : null;
        }
        
        $evaluation = Evaluation::create([
            'employee_id' => $validatedData['employee_id'],
            'evaluator_id' => $validatedData['evaluator_id'],
            'period' => $validatedData['evaluation_period'],
            'evaluation_type' => $validatedData['evaluation_type'],
            'evaluation_date' => now(),
            'due_date' => $validatedData['due_date'] ?? null,
            'criteria_scores' => $criteriaScores,
            'overall_score' => $overallScore,
            'objectives' => $validatedData['objectives'] ?? null,
            'achievements' => $validatedData['achievements'] ?? null,
            'areas_improvement' => $validatedData['areas_improvement'] ?? null,
            'recommendations' => $validatedData['comments'] ?? null,
            'status' => $request->action === 'submit' ? 'submitted' : 'draft'
        ]);
        
        $message = $request->action === 'submit' 
            ? 'Évaluation créée et soumise avec succès.' 
            : 'Évaluation créée avec succès.';
            
        return redirect()->route('hr.evaluations.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['employee', 'evaluator']);
        $criteria = Evaluation::getDefaultCriteria();
        
        return view('evaluations.show', compact('evaluation', 'criteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        $employees = Employee::orderBy('last_name')->get();
        $evaluators = Employee::where('status', 'active')->orderBy('last_name')->get();
        $criteria = Evaluation::getDefaultCriteria();
        
        return view('evaluations.edit', compact('evaluation', 'employees', 'evaluators', 'criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $validatedData = $request->validate([
            'evaluator_id' => 'required|exists:employees,id|different:employee_id',
            'evaluation_type' => 'required|string|in:performance,competency,360_feedback,probation,annual_review',
            'due_date' => 'nullable|date',
            'overall_rating' => 'nullable|integer|min:1|max:5',
            'objectives' => 'nullable|string',
            'achievements' => 'nullable|string',
            'areas_improvement' => 'nullable|string',
            'comments' => 'nullable|string',
            'technical_skills' => 'nullable|integer|min:1|max:5',
            'communication_skills' => 'nullable|integer|min:1|max:5',
            'teamwork_skills' => 'nullable|integer|min:1|max:5',
            'leadership_skills' => 'nullable|integer|min:1|max:5',
            'problem_solving' => 'nullable|integer|min:1|max:5',
            'adaptability' => 'nullable|integer|min:1|max:5',
        ]);
        
        // Prepare criteria scores
        $criteriaScores = [];
        $criteriaKeys = ['technical_skills', 'communication_skills', 'teamwork_skills', 
                        'leadership_skills', 'problem_solving', 'adaptability'];
        
        foreach ($criteriaKeys as $key) {
            if ($request->filled($key)) {
                $criteriaScores[$key] = (int) $request->input($key);
            }
        }
        
        // Calculate overall score if not provided
        $overallScore = $validatedData['overall_rating'] ?? null;
        if (!$overallScore && !empty($criteriaScores)) {
            $total = array_sum($criteriaScores);
            $overallScore = count($criteriaScores) > 0 ? round($total / count($criteriaScores), 2) : null;
        }
        
        $evaluation->update([
            'evaluator_id' => $validatedData['evaluator_id'],
            'evaluation_type' => $validatedData['evaluation_type'],
            'due_date' => $validatedData['due_date'] ?? null,
            'criteria_scores' => $criteriaScores,
            'overall_score' => $overallScore,
            'objectives' => $validatedData['objectives'] ?? null,
            'achievements' => $validatedData['achievements'] ?? null,
            'areas_improvement' => $validatedData['areas_improvement'] ?? null,
            'recommendations' => $validatedData['comments'] ?? null,
            'status' => $request->action === 'submit' ? 'submitted' : $evaluation->status
        ]);
        
        $message = $request->action === 'submit' 
            ? 'Évaluation mise à jour et soumise avec succès.' 
            : 'Évaluation mise à jour avec succès.';
            
        return redirect()->route('hr.evaluations.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        // Only allow deletion of draft evaluations
        if (!$evaluation->isDraft()) {
            return redirect()->route('hr.evaluations.index')
                ->with('error', 'Seules les évaluations en brouillon peuvent être supprimées.');
        }
        
        $evaluation->delete();
        
        return redirect()->route('hr.evaluations.index')
            ->with('success', 'Évaluation supprimée avec succès.');
    }

    /**
     * Submit an evaluation.
     */
    public function submit(Evaluation $evaluation)
    {
        if (!$evaluation->canBeSubmitted()) {
            return back()->with('error', 'Cette évaluation ne peut pas être soumise.');
        }
        
        $evaluation->update(['status' => 'submitted']);
        
        return back()->with('success', 'Évaluation soumise avec succès.');
    }

    /**
     * Acknowledge an evaluation.
     */
    public function acknowledge(Evaluation $evaluation)
    {
        if (!$evaluation->canBeAcknowledged()) {
            return back()->with('error', 'Cette évaluation ne peut pas être confirmée.');
        }
        
        $evaluation->update(['status' => 'acknowledged']);
        
        return back()->with('success', 'Évaluation confirmée avec succès.');
    }

    /**
     * Dispute an evaluation.
     */
    public function dispute(Evaluation $evaluation)
    {
        if (!$evaluation->canBeDisputed()) {
            return back()->with('error', 'Cette évaluation ne peut pas être contestée.');
        }
        
        $evaluation->update(['status' => 'disputed']);
        
        return back()->with('success', 'Contestation enregistrée avec succès.');
    }
}
