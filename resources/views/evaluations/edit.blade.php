@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hr.evaluations.index') }}">Évaluations</a></li>
                        <li class="breadcrumb-item active">Modifier Évaluation</li>
                    </ol>
                </div>
                <h4 class="page-title">Modifier l'Évaluation</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('hr.evaluations.update', $evaluation) }}" method="POST" id="evaluationForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="employee_id">Employé <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="employee_id" id="employee_id" required disabled>
                                        <option value="{{ $evaluation->employee->id }}" selected>{{ $evaluation->employee->full_name }} - {{ $evaluation->employee->currentPosition->title ?? 'N/A' }}</option>
                                    </select>
                                    <input type="hidden" name="employee_id" value="{{ $evaluation->employee->id }}">
                                    <small class="text-muted">L'employé ne peut pas être modifié après création</small>
                                    @error('employee_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="evaluation_period">Période d'Évaluation <span class="text-danger">*</span></label>
                                    <select class="form-control" name="evaluation_period" id="evaluation_period" required disabled>
                                        <option value="{{ $evaluation->period }}" selected>{{ $evaluation->period }}</option>
                                    </select>
                                    <input type="hidden" name="evaluation_period" value="{{ $evaluation->period }}">
                                    <small class="text-muted">La période ne peut pas être modifiée après création</small>
                                    @error('evaluation_period')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="evaluation_type">Type d'Évaluation <span class="text-danger">*</span></label>
                                    <select class="form-control" name="evaluation_type" id="evaluation_type" required>
                                        <option value="">Sélectionner le type</option>
                                        <option value="performance" {{ old('evaluation_type', $evaluation->evaluation_type) == 'performance' ? 'selected' : '' }}>Performance</option>
                                        <option value="competency" {{ old('evaluation_type', $evaluation->evaluation_type) == 'competency' ? 'selected' : '' }}>Compétences</option>
                                        <option value="360_feedback" {{ old('evaluation_type', $evaluation->evaluation_type) == '360_feedback' ? 'selected' : '' }}>Feedback 360°</option>
                                        <option value="probation" {{ old('evaluation_type', $evaluation->evaluation_type) == 'probation' ? 'selected' : '' }}>Période d'essai</option>
                                        <option value="annual_review" {{ old('evaluation_type', $evaluation->evaluation_type) == 'annual_review' ? 'selected' : '' }}>Revue annuelle</option>
                                    </select>
                                    @error('evaluation_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="evaluator_id">Évaluateur <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="evaluator_id" id="evaluator_id" required>
                                        <option value="">Sélectionner un évaluateur</option>
                                        @foreach($evaluators as $evaluator)
                                            <option value="{{ $evaluator->id }}" {{ old('evaluator_id', $evaluation->evaluator_id) == $evaluator->id ? 'selected' : '' }}>{{ $evaluator->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('evaluator_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="due_date">Date d'Échéance</label>
                                    <input type="date" class="form-control" name="due_date" id="due_date" 
                                           value="{{ old('due_date', $evaluation->due_date ? $evaluation->due_date->format('Y-m-d') : '') }}">
                                    @error('due_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="overall_rating">Note Globale (1-5)</label>
                                    <select class="form-control" name="overall_rating" id="overall_rating">
                                        <option value="">À déterminer</option>
                                        <option value="1" {{ old('overall_rating', $evaluation->overall_score) == 1 ? 'selected' : '' }}>1 - Insatisfaisant</option>
                                        <option value="2" {{ old('overall_rating', $evaluation->overall_score) == 2 ? 'selected' : '' }}>2 - Peu satisfaisant</option>
                                        <option value="3" {{ old('overall_rating', $evaluation->overall_score) == 3 ? 'selected' : '' }}>3 - Satisfaisant</option>
                                        <option value="4" {{ old('overall_rating', $evaluation->overall_score) == 4 ? 'selected' : '' }}>4 - Très satisfaisant</option>
                                        <option value="5" {{ old('overall_rating', $evaluation->overall_score) == 5 ? 'selected' : '' }}>5 - Exceptionnel</option>
                                    </select>
                                    @error('overall_rating')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="header-title mb-3">Objectifs et Performance</h5>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="objectives">Objectifs de la Période</label>
                                    <textarea class="form-control" name="objectives" id="objectives" rows="4" 
                                              placeholder="Décrivez les objectifs fixés pour cette période...">{{ old('objectives', $evaluation->objectives) }}</textarea>
                                    @error('objectives')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="achievements">Réalisations</label>
                                    <textarea class="form-control" name="achievements" id="achievements" rows="4" 
                                              placeholder="Décrivez les réalisations et accomplissements...">{{ old('achievements', $evaluation->achievements) }}</textarea>
                                    @error('achievements')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="areas_improvement">Axes d'Amélioration</label>
                                    <textarea class="form-control" name="areas_improvement" id="areas_improvement" rows="3" 
                                              placeholder="Identifiez les domaines nécessitant une amélioration...">{{ old('areas_improvement', $evaluation->areas_improvement) }}</textarea>
                                    @error('areas_improvement')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="header-title mb-3">Compétences Évaluées</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                @foreach(array_slice($criteria, 0, 3) as $key => $criterion)
                                <div class="form-group">
                                    <label for="{{ $key }}">{{ $criterion['name'] }} (1-5)</label>
                                    <select class="form-control" name="{{ $key }}" id="{{ $key }}">
                                        <option value="">Non évalué</option>
                                        @for($i=1;$i<=5;$i++)
                                            <option value="{{ $i }}" {{ old($key, $evaluation->getCriteriaScore($key)) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <small class="text-muted">{{ $criterion['description'] }}</small>
                                </div>
                                @endforeach
                            </div>

                            <div class="col-md-6">
                                @foreach(array_slice($criteria, 3) as $key => $criterion)
                                <div class="form-group">
                                    <label for="{{ $key }}">{{ $criterion['name'] }} (1-5)</label>
                                    <select class="form-control" name="{{ $key }}" id="{{ $key }}">
                                        <option value="">Non évalué</option>
                                        @for($i=1;$i<=5;$i++)
                                            <option value="{{ $i }}" {{ old($key, $evaluation->getCriteriaScore($key)) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <small class="text-muted">{{ $criterion['description'] }}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comments">Commentaires Généraux</label>
                                    <textarea class="form-control" name="comments" id="comments" rows="4" 
                                              placeholder="Commentaires additionnels, recommandations, plan de développement...">{{ old('comments', $evaluation->recommendations) }}</textarea>
                                    @error('comments')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group text-right">
                            <a href="{{ route('hr.evaluations.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                            @if($evaluation->isDraft())
                            <button type="submit" name="action" value="draft" class="btn btn-warning mr-2">
                                <i class="mdi mdi-content-save mr-1"></i>Enregistrer les Modifications
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary">
                                <i class="mdi mdi-send mr-1"></i>Soumettre l'Évaluation
                            </button>
                            @else
                            <button type="submit" name="action" value="draft" class="btn btn-warning mr-2">
                                <i class="mdi mdi-content-save mr-1"></i>Enregistrer les Modifications
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection