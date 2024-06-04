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
        Schema::create('transagciones', function (Blueprint $table) {
            $table->increments('nut');
            $table->integer('IdLivennes');
            $table->string('NUIP');
            $table->date('ProcesoLivines');
            $table->boolean('claseLiviness');
            $table->string('ResultadoLivinessDetencion');
            $table->string('pesoFotografia');
            $table->string('fotografiaCodificada');
            $table->string('fotografiaBase64');
            $table->string('ResultadoCotejo');
            $table->string('codigoUsuarioCotejo');
            $table->string('fotografiaCapturada');
            $table->string('scoreCotrjo');
            $table->date('fechaInicioCotejo');
            $table->date('fechaFinCotejo');
            $table->string('tiempoRespuesta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transagciones');
    }
};
