<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('codarticulo')->nullable();
            $table->string('articulo');
            $table->text('descripcion');
            $table->string('medida');
            $table->decimal('costo', 8, 2);
            $table->decimal('utilidades', 8, 2);
            $table->decimal('precio', 8, 2);
            $table->decimal('alicuota', 8, 2);
            $table->boolean('estado');
            $table->unsignedBigInteger('marca_id');
            $table->unsignedBigInteger('categoria_id');
            $table->timestamps();

            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
