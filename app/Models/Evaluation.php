<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'period',
        'evaluation_date',
        'criteria_scores',
        'strengths',
        'weaknesses',
        'recommendations',
        'overall_score',
        'status',
        'employee_comments',
        'pdf_report',
        'evaluation_type',
        'due_date',
        'objectives',
        'achievements',
        'areas_improvement'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'due_date' => 'date',
        'criteria_scores' => 'json',
        'overall_score' => 'decimal:2'
    ];

    protected $attributes = [
        'status' => 'draft'
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(Employee::class, 'evaluator_id');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'submitted' => 'Soumis',
            'acknowledged' => 'Reconnu',
            'disputed' => 'Contesté',
            default => 'Inconnu'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'submitted' => 'warning',
            'acknowledged' => 'success',
            'disputed' => 'danger',
            default => 'secondary'
        };
    }

    public function getEvaluationTypeTextAttribute()
    {
        return match($this->evaluation_type) {
            'performance' => 'Performance',
            'competency' => 'Compétences',
            '360_feedback' => 'Feedback 360°',
            'probation' => 'Période d\'essai',
            'annual_review' => 'Revue annuelle',
            default => 'Inconnu'
        };
    }

    // Methods
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    public function isAcknowledged()
    {
        return $this->status === 'acknowledged';
    }

    public function isDisputed()
    {
        return $this->status === 'disputed';
    }

    public function canBeSubmitted()
    {
        return $this->status === 'draft';
    }

    public function canBeAcknowledged()
    {
        return $this->status === 'submitted';
    }

    public function canBeDisputed()
    {
        return in_array($this->status, ['submitted', 'acknowledged']);
    }

    // Get default evaluation criteria
    public static function getDefaultCriteria()
    {
        return [
            'technical_skills' => [
                'name' => 'Compétences Techniques',
                'description' => 'Maîtrise des outils, technologies et méthodes du métier',
                'weight' => 25
            ],
            'communication_skills' => [
                'name' => 'Communication',
                'description' => 'Capacité à communiquer efficacement avec les collègues, clients et partenaires',
                'weight' => 15
            ],
            'teamwork_skills' => [
                'name' => 'Travail d\'Équipe',
                'description' => 'Collaboration et contribution à l\'équipe',
                'weight' => 15
            ],
            'leadership_skills' => [
                'name' => 'Leadership',
                'description' => 'Capacité à diriger, motiver et guider les autres',
                'weight' => 20
            ],
            'problem_solving' => [
                'name' => 'Résolution de Problèmes',
                'description' => 'Capacité à identifier, analyser et résoudre les problèmes',
                'weight' => 15
            ],
            'adaptability' => [
                'name' => 'Adaptabilité',
                'description' => 'Capacité à s\'adapter aux changements et à apprendre de nouvelles compétences',
                'weight' => 10
            ]
        ];
    }

    // Get criteria score by key
    public function getCriteriaScore($key)
    {
        $scores = $this->criteria_scores ?? [];
        return $scores[$key] ?? null;
    }

    // Get overall rating text
    public function getOverallRatingTextAttribute()
    {
        if (!$this->overall_score) {
            return 'Non évalué';
        }

        return match(true) {
            $this->overall_score >= 4.5 => 'Exceptionnel',
            $this->overall_score >= 3.5 => 'Très satisfaisant',
            $this->overall_score >= 2.5 => 'Satisfaisant',
            $this->overall_score >= 1.5 => 'Peu satisfaisant',
            default => 'Insatisfaisant'
        };
    }

    // Get overall rating color
    public function getOverallRatingColorAttribute()
    {
        if (!$this->overall_score) {
            return 'gray';
        }

        return match(true) {
            $this->overall_score >= 4.5 => 'green',
            $this->overall_score >= 3.5 => 'blue',
            $this->overall_score >= 2.5 => 'yellow',
            $this->overall_score >= 1.5 => 'orange',
            default => 'red'
        };
    }
}