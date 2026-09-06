<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saas_pagos', function (Blueprint $table) {
            $table->foreignId('estudio_id')
                ->nullable()
                ->after('user_id')
                ->constrained('estudios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('saas_pagos', function (Blueprint $table) {
            $table->dropForeign(['estudio_id']);
            $table->dropColumn('estudio_id');
        });
    }
};
