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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 CAMPOS REALES DE ABOGADO
            $table->string('numero_expediente')->nullable();
            $table->string('juzgado')->nullable();
            $table->string('caratula');
            $table->string('tipo')->nullable(); // civil, penal, laboral
            $table->string('estado')->default('iniciado');

            // 📝 opcionales útiles
            $table->date('fecha_inicio')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
