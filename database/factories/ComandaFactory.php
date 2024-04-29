<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comanda>
 */
class ComandaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mail' => $this->faker->email,
            'solicitud_execucio' => $this->faker->date('Y_m_d'),
            'serveis_opcionals' => $this->faker->word,
            'estat_comanda' => "pendent"
        ];
    }
}
