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
        if (!Schema::hasColumn('saas_pagos', 'checkout_url')) {

            Schema::table('saas_pagos', function (Blueprint $table) {

                $table->text('checkout_url')
                    ->nullable();

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('saas_pagos', 'checkout_url')) {

            Schema::table('saas_pagos', function (Blueprint $table) {

                $table->dropColumn('checkout_url');

            });
        }
    }
};