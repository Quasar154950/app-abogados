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
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            // Relación con socio/cliente
            $table->foreignId('cliente_id')
                ->constrained()
                ->onDelete('cascade');

            // Monto abonado
            $table->decimal('monto', 10, 2)->nullable();

            // Método de pago
            $table->string('metodo_pago')->nullable();

            // Observaciones
            $table->text('observacion')->nullable();

            // Fecha del pago
            $table->date('fecha_pago');

            // Fecha hasta la que queda vigente la cuota
            $table->date('vencimiento_cuota');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
