<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {

            $table->id();

            // Actividad
            $table->string('actividad');

            // Profesor
            $table->string('profesor')->nullable();

            // Día
            $table->date('fecha');

            // Horarios
            $table->time('hora_inicio');
            $table->time('hora_fin');

            // Cupo máximo
            $table->integer('cupo_maximo')->default(10);

            // Activo
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};