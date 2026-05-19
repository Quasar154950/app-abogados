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
        Schema::create('reserva_turnos', function (Blueprint $table) {

            $table->id();

            // Socio
            $table->foreignId('cliente_id')
                ->constrained()
                ->onDelete('cascade');

            // Turno
            $table->foreignId('turno_id')
                ->constrained()
                ->onDelete('cascade');

            // Estado reserva
            $table->string('estado')->default('reservado');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_turnos');
    }
};