<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Paramètres généraux du système
        $systemSettings = [
            // Configuration de l'entreprise
            [
                'setting_key' => 'system.company_name',
                'setting_value' => 'TPT-H INTERNATIONAL',
                'setting_type' => 'system',
                'description' => 'Nom de l\'entreprise'
            ],
            [
                'setting_key' => 'system.company_email',
                'setting_value' => 'contact@tpth.fr',
                'setting_type' => 'system',
                'description' => 'Email de contact de l\'entreprise'
            ],
            [
                'setting_key' => 'system.company_phone',
                'setting_value' => '+33 1 23 45 67 89',
                'setting_type' => 'system',
                'description' => 'Téléphone de l\'entreprise'
            ],
            [
                'setting_key' => 'system.company_address',
                'setting_value' => '15 Rue de la Paix, 75002 Paris, France',
                'setting_type' => 'system',
                'description' => 'Adresse de l\'entreprise'
            ],
            
            // Configuration du système
            [
                'setting_key' => 'system.default_currency',
                'setting_value' => 'EUR',
                'setting_type' => 'system',
                'description' => 'Devise par défaut du système'
            ],
            [
                'setting_key' => 'system.default_language',
                'setting_value' => 'fr',
                'setting_type' => 'system',
                'description' => 'Langue par défaut du système'
            ],
            [
                'setting_key' => 'system.date_format',
                'setting_value' => 'd/m/Y',
                'setting_type' => 'system',
                'description' => 'Format de date par défaut'
            ],
            [
                'setting_key' => 'system.time_format',
                'setting_value' => 'H:i',
                'setting_type' => 'system',
                'description' => 'Format d\'heure par défaut'
            ],
            
            // Configuration de sécurité
            [
                'setting_key' => 'security.password_min_length',
                'setting_value' => 8,
                'setting_type' => 'security',
                'description' => 'Longueur minimale des mots de passe'
            ],
            [
                'setting_key' => 'security.session_timeout',
                'setting_value' => 3600,
                'setting_type' => 'security',
                'description' => 'Délai d\'expiration de session (secondes)'
            ],
            [
                'setting_key' => 'security.two_factor_auth',
                'setting_value' => false,
                'setting_type' => 'security',
                'description' => 'Authentification à deux facteurs'
            ],
            
            // Configuration des emails
            [
                'setting_key' => 'email.from_address',
                'setting_value' => 'noreply@tpth.fr',
                'setting_type' => 'email',
                'description' => 'Adresse email d\'envoi'
            ],
            [
                'setting_key' => 'email.from_name',
                'setting_value' => 'TPT-H ERP',
                'setting_type' => 'email',
                'description' => 'Nom de l\'expéditeur des emails'
            ],
            
            // Configuration des notifications
            [
                'setting_key' => 'notifications.email_enabled',
                'setting_value' => true,
                'setting_type' => 'notifications',
                'description' => 'Activation des notifications par email'
            ],
            [
                'setting_key' => 'notifications.sms_enabled',
                'setting_value' => false,
                'setting_type' => 'notifications',
                'description' => 'Activation des notifications SMS'
            ]
        ];

        foreach ($systemSettings as $setting) {
            Settings::firstOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }
    }
}
