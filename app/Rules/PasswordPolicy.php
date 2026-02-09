<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordPolicy implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check minimum length
        if (strlen($value) < 8) {
            $fail('Le mot de passe doit contenir au moins 8 caractères.');
            return;
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Le mot de passe doit contenir au moins une lettre majuscule.');
            return;
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Le mot de passe doit contenir au moins une lettre minuscule.');
            return;
        }
        
        // Check for at least one digit
        if (!preg_match('/[0-9]/', $value)) {
            $fail('Le mot de passe doit contenir au moins un chiffre.');
            return;
        }
        
        // Check for at least one special character
        if (!preg_match('/[\W_]/', $value)) {
            $fail('Le mot de passe doit contenir au moins un caractère spécial.');
            return;
        }
        
        // Check for common weak passwords
        $weakPasswords = [
            'password', '12345678', 'qwertyui', 'azertyui', 'admin123', 'welcome123'
        ];
        
        $lowerValue = strtolower($value);
        foreach ($weakPasswords as $weakPassword) {
            if (strpos($lowerValue, $weakPassword) !== false) {
                $fail('Le mot de passe est trop faible. Veuillez choisir un mot de passe plus complexe.');
                return;
            }
        }
    }
}