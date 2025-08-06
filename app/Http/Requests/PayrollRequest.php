<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'worked_days' => 'required|numeric|min:0|max:31',
            'overtime_hours' => 'required|numeric|min:0',
            'gross_salary' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'earnings' => 'required|array',
            'earnings.*' => 'numeric|min:0',
            'deductions' => 'required|array',
            'deductions.*' => 'numeric|min:0'
        ];

        if ($this->isMethod('POST')) {
            $rules['employee_id'] = 'required|exists:employees,id';
            $rules['period'] = [
                'required',
                'date_format:Y-m',
                function ($attribute, $value, $fail) {
                    $exists = Payslip::where('employee_id', $this->employee_id)
                        ->whereYear('period', substr($value, 0, 4))
                        ->whereMonth('period', substr($value, 5, 2))
                        ->exists();

                    if ($exists) {
                        $fail('Une fiche de paie existe déjà pour cet employé sur cette période.');
                    }
                }
            ];
            $rules['base_salary'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Veuillez sélectionner un employé.',
            'employee_id.exists' => 'L\'employé sélectionné n\'existe pas.',
            'period.required' => 'Veuillez sélectionner une période.',
            'period.date_format' => 'Le format de la période est invalide.',
            'base_salary.required' => 'Le salaire de base est requis.',
            'base_salary.numeric' => 'Le salaire de base doit être un nombre.',
            'base_salary.min' => 'Le salaire de base doit être supérieur à 0.',
            'worked_days.required' => 'Le nombre de jours travaillés est requis.',
            'worked_days.numeric' => 'Le nombre de jours travaillés doit être un nombre.',
            'worked_days.min' => 'Le nombre de jours travaillés ne peut pas être négatif.',
            'worked_days.max' => 'Le nombre de jours travaillés ne peut pas dépasser 31.',
            'overtime_hours.required' => 'Le nombre d\'heures supplémentaires est requis.',
            'overtime_hours.numeric' => 'Le nombre d\'heures supplémentaires doit être un nombre.',
            'overtime_hours.min' => 'Le nombre d\'heures supplémentaires ne peut pas être négatif.',
            'gross_salary.required' => 'Le salaire brut est requis.',
            'gross_salary.numeric' => 'Le salaire brut doit être un nombre.',
            'gross_salary.min' => 'Le salaire brut doit être supérieur à 0.',
            'net_salary.required' => 'Le salaire net est requis.',
            'net_salary.numeric' => 'Le salaire net doit être un nombre.',
            'net_salary.min' => 'Le salaire net doit être supérieur à 0.',
            'earnings.required' => 'Les gains sont requis.',
            'earnings.array' => 'Format invalide pour les gains.',
            'earnings.*.numeric' => 'Les montants des gains doivent être des nombres.',
            'earnings.*.min' => 'Les montants des gains ne peuvent pas être négatifs.',
            'deductions.required' => 'Les déductions sont requises.',
            'deductions.array' => 'Format invalide pour les déductions.',
            'deductions.*.numeric' => 'Les montants des déductions doivent être des nombres.',
            'deductions.*.min' => 'Les montants des déductions ne peuvent pas être négatifs.'
        ];
    }
}