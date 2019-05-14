<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('razonsocial');
            $table->bigInteger('documentounico')->unique();
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('email')->unique()->nullable();
            $table->integer('codigopostal');
            $table->string('localidad');
            $table->string('provincia');
            $table->string('condicioniva');
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
        Schema::dropIfExists('clientes');
    }
}
