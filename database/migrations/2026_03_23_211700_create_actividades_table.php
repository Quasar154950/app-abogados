<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            
            // Quién hizo el cambio
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Qué pasó (ej: "Se actualizó el estado")
            $table->string('descripcion');
            
            // Tipo de acción: 'created', 'updated', 'deleted'
            $table->string('accion');

            // Esto crea automáticamente 'logueable_id' y 'logueable_type'
            // Es lo que permite que el log sirva para Clientes, Seguimientos, etc.
            $table->morphs('logueable');

            // Para guardar los valores antes y después del cambio
            $table->json('antes')->nullable();
            $table->json('despues')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
