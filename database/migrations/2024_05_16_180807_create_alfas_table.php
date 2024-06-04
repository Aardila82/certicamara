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
        Schema::create('alfas', function (Blueprint $table) {
            $table->string('pin', 20)->primary();
            $table->string('nombre1', 50)->nullable(false);
            $table->string('nombre2', 50)->nullable();
            $table->string('partícula', 10)->nullable();
            $table->string('apellido1', 50)->nullable(false);
            $table->string('apellido2', 50)->nullable();
            $table->string('explugar', 100)->nullable(false);
            $table->date('expfecha')->nullable(false);
            $table->string('vigencia', 20)->nullable(false)->default('some value');
            $table->date('updated_at')->nullable(false);
            $table->date('created_at')->nullable(false);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alfas');
    }
};
