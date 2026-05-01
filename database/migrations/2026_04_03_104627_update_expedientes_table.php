<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            if (!Schema::hasColumn('expedientes', 'numero_expediente')) {
                $table->string('numero_expediente')->nullable()->after('cliente_id');
            }

            if (!Schema::hasColumn('expedientes', 'juzgado')) {
                $table->string('juzgado')->nullable()->after('numero_expediente');
            }

            if (!Schema::hasColumn('expedientes', 'caratula')) {
                $table->string('caratula')->nullable()->after('juzgado');
            }

            if (!Schema::hasColumn('expedientes', 'tipo')) {
                $table->string('tipo')->nullable()->after('caratula');
            }
        });

        if (
            Schema::hasColumn('expedientes', 'titulo') &&
            Schema::hasColumn('expedientes', 'caratula')
        ) {
            DB::statement('UPDATE expedientes SET caratula = titulo WHERE caratula IS NULL');
        }

        Schema::table('expedientes', function (Blueprint $table) {
            if (Schema::hasColumn('expedientes', 'titulo')) {
                $table->dropColumn('titulo');
            }

            if (Schema::hasColumn('expedientes', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            if (!Schema::hasColumn('expedientes', 'titulo')) {
                $table->string('titulo')->nullable();
            }

            if (!Schema::hasColumn('expedientes', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }
        });

        if (
            Schema::hasColumn('expedientes', 'titulo') &&
            Schema::hasColumn('expedientes', 'caratula')
        ) {
            DB::statement('UPDATE expedientes SET titulo = caratula WHERE titulo IS NULL');
        }

        Schema::table('expedientes', function (Blueprint $table) {
            foreach (['numero_expediente', 'juzgado', 'caratula', 'tipo'] as $column) {
                if (Schema::hasColumn('expedientes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};