<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Seeder;

class CategoriasSistemaSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasIngreso = [
            'Empleo',
            'Freelance / Proyecto',
            'Negocio Propio',
            'Inversión / Dividendos',
            'Bono / Extra',
            'Otro Ingreso',
        ];

        $categoriasEgreso = [
            'Vivienda' => ['Alquiler', 'Agua', 'Luz', 'Internet'],
            'Educación' => ['Universidad', 'Cursos', 'Libros'],
            'Alimentación' => ['Supermercado', 'Restaurante', 'Almuerzo'],
            'Transporte' => ['Gasolina', 'Bus', 'Taxi / Uber', 'Parqueo'],
            'Salud' => ['Consulta', 'Medicamentos', 'Laboratorio'],
            'Ocio / Entretenimiento' => ['Suscripciones', 'Cine', 'Salidas'],
            'Deporte' => ['Gimnasio', 'Equipo deportivo'],
            'Imprevistos' => ['Emergencias', 'Reparaciones'],
            'Otro Egreso' => [],
        ];

        foreach ($categoriasIngreso as $nombre) {
            Categoria::query()->firstOrCreate([
                'user_id' => null,
                'nombre' => $nombre,
                'tipo' => 'ingreso',
            ]);
        }

        foreach ($categoriasEgreso as $nombreCategoria => $subcategorias) {
            $categoria = Categoria::query()->firstOrCreate([
                'user_id' => null,
                'nombre' => $nombreCategoria,
                'tipo' => 'egreso',
            ]);

            foreach ($subcategorias as $nombreSubcategoria) {
                Subcategoria::query()->firstOrCreate([
                    'categoria_id' => $categoria->id,
                    'nombre' => $nombreSubcategoria,
                ]);
            }
        }
    }
}
