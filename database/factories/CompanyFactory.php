<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'raison_sociale' => $this->faker->company,
            'type' => $this->faker->randomElement(['holding', 'filiale']),
            'niu' => $this->faker->unique()->numerify('NIU########'),
            'rccm' => $this->faker->unique()->numerify('RCCM########'),
            'regime_fiscal' => $this->faker->randomElement(['IR', 'IS', 'IR+IS']),
            'secteur_activite' => $this->faker->randomElement(['Agroalimentaire', 'Services', 'Production', 'Commerce']),
            'devise' => 'XAF',
            'pays' => 'Cameroun',
            'ville' => $this->faker->city,
            'siege_social' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->phoneNumber,
            'whatsapp' => $this->faker->phoneNumber,
            'site_web' => $this->faker->url,
            'parent_id' => null,
            'logo' => null,
            'visuel' => null,
            'active' => $this->faker->boolean(80)
        ];
    }

    public function holding()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'holding',
            ];
        });
    }

    public function filiale()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'filiale',
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }
}