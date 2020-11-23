<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteArticuloDeudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_articulo_deudas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_articulo_id');
            $table->string("cantidad_usd")->required();
            $table->foreign('cliente_articulo_id')->references('id')->on('cliente_articulos')->onDelete('cascade');
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
        Schema::dropIfExists('cliente_articulo_deudas');
    }
}
