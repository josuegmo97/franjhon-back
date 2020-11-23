<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_articulos', function (Blueprint $table) {
            $table->id();
            $table->string('articulo_id')->required();
            $table->string("cliente_id")->required();
            $table->string("deuda_usd")->nullable();
            $table->string("cantidad")->nullable();
            $table->boolean("lote_pagado")->nullable();
            $table->foreign('articulo_id')->references("slug")->on('articulos');
            $table->foreign('cliente_id')->references("cedula")->on('clientes');
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
        Schema::dropIfExists('cliente_articulos');
    }
}
