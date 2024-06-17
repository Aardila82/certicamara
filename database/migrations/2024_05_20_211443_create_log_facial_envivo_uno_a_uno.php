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
        Schema::create('log_facial_envivo_uno_a_uno', function (Blueprint $table) {
            $table->id();
            $table->string('nut');
            $table->string('nuip');
            $table->string('resultado');
            $table->date('fechafin');
            $table->unsignedBigInteger('idusuario'); // Cambiar a unsignedBigInteger
            $table->string('hashalgo');
            $table->enum('idmasiva');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_facial_envivo_uno_a_uno');
    }
};
