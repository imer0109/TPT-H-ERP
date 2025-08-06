<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ValidationService
{
    public static function validateUser(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    public static function validateTransaction(array $data)
    {
        return Validator::make($data, [
            'montant' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:encaissement,decaissement'],
            'libelle' => ['required', 'string', 'max:255'],
            'justificatif' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'caisse_id' => ['required', 'exists:caisses,id'],
        ]);
    }

    public static function validateCashRegister(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:principale,secondaire'],
            'solde_initial' => ['required', 'numeric', 'min:0'],
            'entite_id' => ['required', 'exists:entites,id'],
        ]);
    }
}