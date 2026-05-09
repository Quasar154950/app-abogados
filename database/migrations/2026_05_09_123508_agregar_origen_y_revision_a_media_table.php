<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('subido_por')->default('estudio')->after('custom_properties');
            $table->boolean('revisado_por_estudio')->default(true)->after('subido_por');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn([
                'subido_por',
                'revisado_por_estudio',
            ]);
        });
    }
};
