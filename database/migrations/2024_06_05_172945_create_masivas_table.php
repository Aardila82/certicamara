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
        Schema::create('masivas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechainicio');
            $table->dateTime('fechafin');
            $table->unsignedBigInteger('usuariocarga_id');
            $table->integer('totalregistros');
            $table->integer('errortotalregistros');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masivas');
    }
};
