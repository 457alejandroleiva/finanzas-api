<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Ingreso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingreso>
 */
class IngresoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'categoria_id' => Categoria::factory()->ingreso(),
            'fecha' => fake()->dateTimeBetween('-1 year', 'now'),
            'fuente' => fake()->randomElement(['Trabajo de medio tiempo', 'Freelance', 'Beca']),
            'monto' => sprintf('%d.%02d', fake()->numberBetween(500, 5000), fake()->numberBetween(0, 99)),
            'notas' => fake()->optional()->sentence(),
        ];
    }
}
