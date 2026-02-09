<?php

namespace App\Http\Requests;

use App\Decorators\ApiRequestDecorator;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends ApiRequestDecorator
{
    /**
     * @inheritDoc
     */
    public static function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'matricule' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'in:M,F'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'cnss_number' => ['nullable', 'string', 'max:50'],
            'id_card_number' => ['nullable', 'string', 'max:50'],
            'nui_number' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'current_company_id' => ['nullable', 'exists:companies,id'],
            'current_agency_id' => ['nullable', 'exists:agencies,id'],
            'current_warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'current_position_id' => ['nullable', 'exists:positions,id'],
            'supervisor_id' => ['nullable', 'exists:employees,id'],
            'salaire_base' => ['nullable', 'numeric', 'min:0'],
            'date_embauche' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
            'biometric_id' => ['nullable', 'string', 'max:50'],
            'schedule_start' => ['nullable', 'string'],
            'schedule_end' => ['nullable', 'string'],
        ];

        // Add unique validation for matricule, excluding current employee for updates
        $request = request();
        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            // For update operations, exclude the current employee
            $employee = $request->route('employee');
            if ($employee && is_object($employee)) {
                $employeeId = $employee->id;
            } elseif ($employee && is_scalar($employee)) {
                $employeeId = $employee;
            } else {
                $employeeId = null;
            }
            
            if ($employeeId) {
                $rules['matricule'][] = "unique:employees,matricule," . $employeeId;
            } else {
                $rules['matricule'][] = 'unique:employees,matricule';
            }
        } else {
            // For create operations, ensure uniqueness across all employees
            $rules['matricule'][] = 'unique:employees,matricule';
        }

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public static function attributes(): array
    {
        return [
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'matricule' => 'Matricule',
            'gender' => 'Genre',
            'birth_date' => 'Date de naissance',
            'birth_place' => 'Lieu de naissance',
            'photo' => 'Photo',
            'nationality' => 'Nationalité',
            'cnss_number' => 'Numéro CNSS',
            'id_card_number' => 'Numéro de carte d\'identité',
            'nui_number' => 'Numéro NUI',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'address' => 'Adresse',
            'current_company_id' => 'Entreprise',
            'current_agency_id' => 'Agence',
            'current_warehouse_id' => 'Dépôt',
            'current_position_id' => 'Poste',
            'supervisor_id' => 'Superviseur',
            'salaire_base' => 'Salaire de base',
            'date_embauche' => 'Date d\'embauche',
            'status' => 'Statut',
            'biometric_id' => 'ID biométrique',
            'schedule_start' => 'Début du planning',
            'schedule_end' => 'Fin du planning',
        ];
    }
}