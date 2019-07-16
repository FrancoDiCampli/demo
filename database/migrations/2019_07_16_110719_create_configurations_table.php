<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuit');
            $table->string('razonsocial');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->integer('codigopostal');
            $table->string('localidad');
            $table->string('provincia');
            $table->string('inicioactividades');
            $table->string('nombrefantasia')->nullable();
            $table->string('domiciliocomercial')->nullable();
            $table->string('tagline')->nullable();
            $table->string('cert')->nullable();
            $table->string('key')->nullable();
            $table->string('numfactura')->nullable();
            $table->string('numremito')->nullable();
            $table->string('numpresupuesto')->nullable();
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
        Schema::dropIfExists('configurations');
    }
}
