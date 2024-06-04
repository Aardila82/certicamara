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
        Schema::create('log_sesiones', function (Blueprint $table) {
            $table->id();
            $table->integer('idUsuario');
            $table->integer('idRol');
            $table->float('IpMaquina');
            $table->string('tipoMaquina');
            $table->date('inicio');
            $table->date('fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_sesiones');
    }
};
