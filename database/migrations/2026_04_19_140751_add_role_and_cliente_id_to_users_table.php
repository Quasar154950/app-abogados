<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('abogado')->after('email');
            }

            if (!Schema::hasColumn('users', 'cliente_id')) {
                $table->foreignId('cliente_id')
                    ->nullable()
                    ->constrained('clientes')
                    ->nullOnDelete()
                    ->after('role');
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cliente_id')) {
                $table->dropConstrainedForeignId('cliente_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};