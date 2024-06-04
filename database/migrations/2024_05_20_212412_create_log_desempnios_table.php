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
        Schema::create('log_desempnios', function (Blueprint $table) {
            $table->id();
            $table->integer('NUIP');
            $table->string('resultado');
            $table->float('scoreCotejo');
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->float('tiempoRespuesta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_desempnios');
    }
};
