<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@example.test',
                'ingreso_categoria' => 'Empleo',
                'ingreso_fuente' => 'Trabajo de medio tiempo',
                'ingresos' => [2500, 2500, 2700, 2600, 2800, 2900],
            ],
            [
                'name' => 'Carlos Pérez',
                'email' => 'carlos.perez@example.test',
                'ingreso_categoria' => 'Freelance / Proyecto',
                'ingreso_fuente' => 'Diseño freelance',
                'ingresos' => [2200, 2400, 2300, 2500, 2600, 2700],
            ],
        ];

        $categorias = Categoria::query()
            ->whereNull('user_id')
            ->get()
            ->keyBy(fn (Categoria $categoria): string => "{$categoria->tipo}|{$categoria->nombre}");

        $subcategorias = Subcategoria::query()
            ->get()
            ->keyBy(fn (Subcategoria $subcategoria): string => "{$subcategoria->categoria_id}|{$subcategoria->nombre}");

        $egresosMensuales = [
            ['Vivienda', 'Alquiler', 'Alquiler de habitación', 850],
            ['Alimentación', 'Supermercado', 'Compra de supermercado', 425],
            ['Transporte', 'Bus', 'Pasajes de bus', 145],
            ['Educación', 'Universidad', 'Cuota universitaria', 750],
            ['Salud', 'Medicamentos', 'Medicamentos', 85],
            ['Ocio / Entretenimiento', 'Suscripciones', 'Suscripciones digitales', 65],
            ['Otro Egreso', null, 'Gastos varios', 120],
        ];

        foreach ($usuarios as $indiceUsuario => $datosUsuario) {
            $usuario = User::query()->updateOrCreate(
                ['email' => $datosUsuario['email']],
                [
                    'name' => $datosUsuario['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('Prueba2026!'),
                ],
            );

            $usuario->ingresos()
                ->whereBetween('fecha', ['2026-06-01', '2026-06-30'])
                ->delete();

            $usuario->egresos()
                ->whereBetween('fecha', ['2026-06-01', '2026-06-30'])
                ->delete();

            foreach ([1, 2, 3, 4, 5, 7] as $indiceMes => $mes) {
                $fechaIngreso = sprintf('2026-%02d-05', $mes);
                $categoriaIngreso = $categorias["ingreso|{$datosUsuario['ingreso_categoria']}"];

                $usuario->ingresos()->updateOrCreate(
                    [
                        'fecha' => $fechaIngreso,
                        'fuente' => $datosUsuario['ingreso_fuente'],
                    ],
                    [
                        'categoria_id' => $categoriaIngreso->id,
                        'monto' => $datosUsuario['ingresos'][$indiceMes].'.00',
                        'notas' => 'Datos de prueba para el dashboard.',
                    ],
                );

                foreach ($egresosMensuales as $indiceEgreso => [$nombreCategoria, $nombreSubcategoria, $descripcion, $montoBase]) {
                    $categoria = $categorias["egreso|{$nombreCategoria}"];
                    $subcategoriaId = $nombreSubcategoria === null
                        ? null
                        : $subcategorias["{$categoria->id}|{$nombreSubcategoria}"]->id;

                    $monto = $montoBase + ($indiceUsuario * 25) + ($mes * 10);
                    $fechaEgreso = sprintf('2026-%02d-%02d', $mes, 10 + $indiceEgreso);

                    $usuario->egresos()->updateOrCreate(
                        [
                            'fecha' => $fechaEgreso,
                            'descripcion' => $descripcion,
                        ],
                        [
                            'categoria_id' => $categoria->id,
                            'subcategoria_id' => $subcategoriaId,
                            'monto' => $monto.'.00',
                            'notas' => 'Datos de prueba para el dashboard.',
                        ],
                    );
                }
            }
        }
    }
}
