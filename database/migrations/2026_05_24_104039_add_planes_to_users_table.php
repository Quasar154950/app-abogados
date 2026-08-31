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
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'plan')) {
                $table->string('plan')
                    ->default('basico');
            }

            if (!Schema::hasColumn('users', 'precio_suscripcion')) {
                $table->integer('precio_suscripcion')
                    ->default(0);
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'precio_suscripcion')) {
                $table->dropColumn('precio_suscripcion');
            }

            if (Schema::hasColumn('users', 'plan')) {
                $table->dropColumn('plan');
            }

        });
    }
};