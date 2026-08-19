<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre' => fake()->unique()->words(2, true),
            'tipo' => 'egreso',
        ];
    }

    public function ingreso(): static
    {
        return $this->state(fn (): array => ['tipo' => 'ingreso']);
    }

    public function egreso(): static
    {
        return $this->state(fn (): array => ['tipo' => 'egreso']);
    }

    public function sistema(): static
    {
        return $this->state(fn (): array => ['user_id' => null]);
    }
}
