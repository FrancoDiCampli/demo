<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ptoventa');
            $table->bigInteger('numfactura');
            $table->bigInteger('cuit');//cliente
            $table->string('fecha');
            $table->decimal('bonificacion', 8, 2);
            $table->decimal('recargo', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->decimal('total', 8, 2);
            $table->string('pagada');
            $table->string('condicionventa');
            $table->bigInteger('comprobanteafip')->nullable();
            $table->bigInteger('cae')->nullable();
            $table->string('fechavto')->nullable();
            $table->string('codbarra')->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->string('compago')->nullable();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
