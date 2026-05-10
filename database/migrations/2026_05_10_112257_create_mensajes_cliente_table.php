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
        Schema::create('mensajes_cliente', function (Blueprint $table) {

            $table->id();

            // Cliente relacionado
            $table->foreignId('cliente_id')
                ->constrained()
                ->cascadeOnDelete();

            // Usuario que envía (abogado)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Mensaje
            $table->text('mensaje');

            // Quién lo envió
            $table->enum('remitente', [
                'cliente',
                'estudio'
            ]);

            // Leído
            $table->boolean('leido')
                ->default(false);

            // Fecha lectura
            $table->timestamp('leido_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_cliente');
    }
};
