<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->string('numero_expediente')->nullable()->after('cliente_id');
            $table->string('juzgado')->nullable()->after('numero_expediente');
            $table->string('caratula')->nullable()->after('juzgado');
            $table->string('tipo')->nullable()->after('caratula');
        });

        // Copiamos el valor viejo de "titulo" a "caratula"
        DB::statement('UPDATE expedientes SET caratula = titulo WHERE caratula IS NULL');

        Schema::table('expedientes', function (Blueprint $table) {
            $table->dropColumn(['titulo', 'descripcion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
        });

        // Si volvemos atrás, recuperamos "titulo" desde "caratula"
        DB::statement('UPDATE expedientes SET titulo = caratula WHERE titulo IS NULL');

        Schema::table('expedientes', function (Blueprint $table) {
            $table->dropColumn(['numero_expediente', 'juzgado', 'caratula', 'tipo']);
        });
    }
};
