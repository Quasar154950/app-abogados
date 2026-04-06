<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            // Agregamos el casillero "is_pinned"
            // Por defecto será 0 (falso/no fijado)
            $table->boolean('is_pinned')->default(false)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            // Si nos arrepentimos, esto borra la columna
            $table->dropColumn('is_pinned');
        });
    }
};
