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
        Schema::create('log_livnes', function (Blueprint $table) {
            $table->id();
            $table->integer('NUT');
            $table->integer('NUIP');
            $table->integer('idLivness');
            $table->date('fecha');
            $table->string('clase');
            $table->boolean('estado');
            $table->string('resultadoLiveness');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_livnes');
    }
};
