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
        Schema::create('log_alfas', function (Blueprint $table) {
            $table->id(); // Primary key (id)
            $table->string('nombre_archivo');
            $table->string('usuario_inicio');
            $table->timestamp('fecha_inicio_transaccion');
            $table->timestamp('fecha_final');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_alfas');
    }
};
