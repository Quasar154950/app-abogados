<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nombre_estudio')) {
                $table->string('nombre_estudio')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'logo_estudio')) {
                $table->string('logo_estudio')->nullable()->after('nombre_estudio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'logo_estudio')) {
                $table->dropColumn('logo_estudio');
            }

            if (Schema::hasColumn('users', 'nombre_estudio')) {
                $table->dropColumn('nombre_estudio');
            }
        });
    }
};