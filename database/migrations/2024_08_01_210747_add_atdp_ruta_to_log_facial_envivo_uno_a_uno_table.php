<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtdpRutaToLogFacialEnvivoUnoAUnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_facial_envivo_uno_a_uno', function (Blueprint $table) {
            $table->string('atdpruta')->nullable()->after('response'); // Cambia 'last_column' por la última columna existente en la tabla
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_facial_envivo_uno_a_uno', function (Blueprint $table) {
            $table->dropColumn('atdpruta');
        });
    }
}
