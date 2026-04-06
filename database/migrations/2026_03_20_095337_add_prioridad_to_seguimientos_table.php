<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seguimientos', function (Blueprint $columna) {
            // Agregamos la prioridad con 'media' por defecto para no romper los registros viejos
            $columna->string('prioridad')->default('media')->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('seguimientos', function (Blueprint $columna) {
            $columna->dropColumn('prioridad');
        });
    }
};
