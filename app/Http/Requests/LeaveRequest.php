<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048'
        ];
        
        // Only require employee_id if user has permission to create leave for others
        if (Auth::user()->can('create-leave-for-others')) {
            $rules['employee_id'] = 'required|exists:employees,id';
        }
        
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'L\'employé est requis.',
            'employee_id.exists' => 'L\'employé sélectionné est invalide.',
            'leave_type_id.required' => 'Le type de congé est requis.',
            'leave_type_id.exists' => 'Le type de congé sélectionné est invalide.',
            'start_date.required' => 'La date de début est requise.',
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou une date future.',
            'end_date.required' => 'La date de fin est requise.',
            'end_date.after' => 'La date de fin doit être après la date de début.',
            'reason.max' => 'La raison ne doit pas dépasser 500 caractères.',
            'supporting_document.file' => 'Le document justificatif doit être un fichier.',
            'supporting_document.mimes' => 'Le document justificatif doit être un fichier de type: pdf, jpg, jpeg, png, doc, docx.',
            'supporting_document.max' => 'Le document justificatif ne doit pas dépasser 2MB.'
        ];
    }
}