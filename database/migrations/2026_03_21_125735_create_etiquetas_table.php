<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('color')->default('#3b82f6');
            $table->timestamps();
        });

        Schema::table('seguimientos', function (Blueprint $table) {
            $table->foreignId('etiqueta_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->dropForeign(['etiqueta_id']);
            $table->dropColumn('etiqueta_id');
        });
        Schema::dropIfExists('etiquetas');
    }
};
