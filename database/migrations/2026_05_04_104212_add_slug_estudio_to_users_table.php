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
        // Evita error si la columna ya existe
        if (!Schema::hasColumn('users', 'slug_estudio')) {

            Schema::table('users', function (Blueprint $table) {
                $table->string('slug_estudio')
                      ->nullable()
                      ->after('logo_estudio');
            });

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Evita error si la columna no existe
        if (Schema::hasColumn('users', 'slug_estudio')) {

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('slug_estudio');
            });

        }
    }
};
