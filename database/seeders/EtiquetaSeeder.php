<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EtiquetaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('etiquetas')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('etiquetas')->insert([
            ['nombre' => 'Venta', 'color' => '#8b5cf6', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cobranza', 'color' => '#10b981', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Reclamo', 'color' => '#ef4444', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Consulta', 'color' => '#3b82f6', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}