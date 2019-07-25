<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInicialsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inicialsettings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuit');
            $table->string('razonsocial');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->integer('codigopostal');
            $table->string('localidad');
            $table->string('provincia');
            $table->string('condicioniva');
            $table->string('inicioactividades');
            $table->integer('puntoventa');
            $table->string('nombrefantasia')->nullable();
            $table->string('domiciliocomercial')->nullable();
            $table->string('tagline')->nullable();
            $table->string('cert')->nullable();
            $table->string('key')->nullable();
            $table->bigInteger('numfactura')->nullable();
            $table->bigInteger('numremito')->nullable();
            $table->bigInteger('numpresupuesto')->nullable();
            $table->bigInteger('numpago')->nullable();
            $table->bigInteger('numrecibo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inicialsettings');
    }
}
