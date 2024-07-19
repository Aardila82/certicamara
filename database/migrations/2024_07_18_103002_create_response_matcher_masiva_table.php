<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseMatcherMasivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_matcher_masiva', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_resultado');
            $table->string('nut');
            $table->string('nuip');
            $table->string('id_log');
            $table->string('id_oaid');
            $table->string('id_cliente');
            $table->string('resultado_cotejo');
            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('codigo_particula');
            $table->string('descripcion_particula');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('lugar_expedicion');
            $table->string('fecha_expedicion');
            $table->string('codigo_vigencia');
            $table->string('descripcion_vigencia');
            $table->string('message_error')->nullable();
            $table->integer('idunoauno');
            $table->integer('idmasiva');
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
        Schema::dropIfExists('response_matcher_masiva');
    }
}
