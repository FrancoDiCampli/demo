<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientocuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientocuentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ctacte_id');
            $table->string('tipo');
            $table->string('fecha');
            $table->timestamps();

            $table->foreign('ctacte_id')->references('id')->on('cuentacorrientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientocuentas');
    }
}
