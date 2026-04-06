<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->string('estado')->default('pendiente')->after('descripcion');
            $table->date('fecha_recordatorio')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->dropColumn(['estado', 'fecha_recordatorio']);
        });
    }
};
