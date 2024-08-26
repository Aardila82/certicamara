<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosConexionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_conexion', function (Blueprint $table) {
            $table->id();
            $table->string('so'); // Sistema Operativo
            $table->string('navegador');
            $table->string('ubicacion_geografica');
            $table->string('ipv4');
            $table->string('identificador_unico_dispositivo');
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_conexion');
    }
}
