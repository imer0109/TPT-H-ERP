<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description'
    ];

    protected $casts = [
        'setting_value' => 'array', // Pour permettre le stockage de valeurs complexes si nécessaire
    ];

    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set setting value by key
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $description
     * @return void
     */
    public static function setValue($key, $value, $type = 'general', $description = null)
    {
        self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_type' => $type,
                'description' => $description
            ]
        );
    }

    /**
     * Get accounting specific settings
     *
     * @return array
     */
    public static function getAccountingSettings()
    {
        $settings = [
            'fiscal_year_start' => self::getValue('accounting.fiscal_year_start'),
            'fiscal_year_end' => self::getValue('accounting.fiscal_year_end'),
            'default_currency' => self::getValue('accounting.default_currency', 'EUR'),
            'auto_numbering' => self::getValue('accounting.auto_numbering', true),
            'validation_required' => self::getValue('accounting.validation_required', true),
        ];

        // Définir des valeurs par défaut si elles n'existent pas encore
        if (!$settings['fiscal_year_start']) {
            $settings['fiscal_year_start'] = date('Y') . '-01-01';
        }
        
        if (!$settings['fiscal_year_end']) {
            $settings['fiscal_year_end'] = date('Y') . '-12-31';
        }

        return $settings;
    }

    /**
     * Save accounting specific settings
     *
     * @param array $data
     * @return void
     */
    public static function saveAccountingSettings($data)
    {
        self::setValue('accounting.fiscal_year_start', $data['fiscal_year_start'], 'accounting', 'Date de début de l\'exercice comptable');
        self::setValue('accounting.fiscal_year_end', $data['fiscal_year_end'], 'accounting', 'Date de fin de l\'exercice comptable');
        self::setValue('accounting.default_currency', $data['default_currency'], 'accounting', 'Devise par défaut pour la comptabilité');
        self::setValue('accounting.auto_numbering', isset($data['auto_numbering']), 'accounting', 'Activer la numérotation automatique des écritures');
        self::setValue('accounting.validation_required', isset($data['validation_required']), 'accounting', 'Exiger la validation des écritures comptables');
    }
}