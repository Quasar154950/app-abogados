<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtiquetasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('etiquetas')->insert([
            [
                'nombre' => 'Escrito',
                'color' => '#7c3aed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Audiencia',
                'color' => '#f97316',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Seguimiento',
                'color' => '#2563eb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Documentación',
                'color' => '#eab308',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Urgente',
                'color' => '#dc2626',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cliente',
                'color' => '#16a34a',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
