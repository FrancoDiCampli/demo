<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ctacte_id');
            $table->integer('numrecibo');
            $table->string('fecha');
            $table->decimal('total', 8, 2);
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
        Schema::dropIfExists('recibos');
    }
}
