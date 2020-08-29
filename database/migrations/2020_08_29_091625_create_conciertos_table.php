<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConciertosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciertos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_promotor');
            $table->unsignedBigInteger('id_recinto');
            $table->string('nombre');
            $table->integer('numero_espectadores');
            $table->dateTime('fecha');
            $table->integer('rentabilidad')->nullable();

            $table->foreign('id_promotor')->references('id')->on('promotores');
            $table->foreign('id_recinto')->references('id')->on('recintos');
        });

        Schema::create('grupos_conciertos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('id_concierto');

            $table->foreign('id_grupo')->references('id')->on('grupos');
            $table->foreign('id_concierto')->references('id')->on('conciertos');
        });

        Schema::create('grupos_medios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_medio');
            $table->unsignedBigInteger('id_concierto');

            $table->foreign('id_medio')->references('id')->on('medios');
            $table->foreign('id_concierto')->references('id')->on('conciertos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conciertos');
    }
}
