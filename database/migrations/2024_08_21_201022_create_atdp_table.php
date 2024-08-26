<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtdpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atdp', function (Blueprint $table) {
            $table->id();
            $table->string('nut');
            $table->string('estado_aprobacion');
            $table->string('cedula_aprobacion');
            $table->string('enlace_atdp');
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
        Schema::dropIfExists('atdp');
    }
}
