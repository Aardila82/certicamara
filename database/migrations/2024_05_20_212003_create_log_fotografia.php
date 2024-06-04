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
        Schema::create('log_fotografia', function (Blueprint $table) {
            $table->id();
            $table->integer('fnut');
            $table->integer('nuip');
            $table->float('peso_real');
            $table->string('hash');
            $table->string('fotografia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_fotografia');
    }
};
