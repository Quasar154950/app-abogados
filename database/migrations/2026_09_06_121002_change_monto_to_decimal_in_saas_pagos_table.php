<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saas_pagos', function (Blueprint $table) {
            $table->decimal('monto', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('saas_pagos', function (Blueprint $table) {
            $table->integer('monto')->change();
        });
    }
};
