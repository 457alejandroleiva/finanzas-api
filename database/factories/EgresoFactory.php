<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Egreso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Egreso>
 */
class EgresoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'categoria_id' => Categoria::factory()->egreso(),
            'subcategoria_id' => null,
            'fecha' => fake()->dateTimeBetween('-1 year', 'now'),
            'descripcion' => fake()->sentence(3),
            'monto' => sprintf('%d.%02d', fake()->numberBetween(25, 2000), fake()->numberBetween(0, 99)),
            'notas' => fake()->optional()->sentence(),
        ];
    }
}
