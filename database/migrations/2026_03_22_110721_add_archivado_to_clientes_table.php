<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Agregamos la columna 'archivado'. 
            // 0 significa Activo, 1 significa Archivado.
            $table->boolean('archivado')->default(false)->after('id'); 
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('archivado');
        });
    }
};
