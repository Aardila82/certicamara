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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre1');
            $table->string('nombre2');
            $table->string('apellido1');
            $table->string('apellido2');
            $table->integer('numerodedocumento');
            $table->string('email');
            $table->string('telefono');
            $table->unsignedBigInteger('departamento');
            $table->unsignedBigInteger('municipio');
            $table->string('usuario');
            $table->string('contrasena');
            $table->integer('rol');
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('departamento')->references('id')->on('departamentos')->onDelete('cascade');
            $table->foreign('municipio')->references('id')->on('municipios')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
