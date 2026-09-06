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
        Schema::table('estudios', function (Blueprint $table) {
            $table->dateTime('fecha_vencimiento')->nullable();
            $table->string('plan')->nullable();
            $table->decimal('precio_suscripcion', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudios', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_vencimiento',
                'plan',
                'precio_suscripcion',
            ]);
        });
    }
};