<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('articulo_id')->required();
            $table->integer('cantidad')->nullable();
            $table->string("pcu_usd")->nullable();
            $table->string("pcu_ves")->nullable();
            $table->string("valor_inicial_usd")->nullable();
            $table->string("valor_inicial_ves")->nullable();
            $table->string("pvu_usd")->nullable();
            $table->string("pvu_ves")->nullable();
            $table->string("valor_final_usd")->nullable();
            $table->string("valor_final_ves")->nullable();
            $table->boolean("lote_pagado")->nullable();
            $table->string("tipo_pago")->nullable();
            $table->foreign('articulo_id')->references("slug")->on('articulos');
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
        Schema::dropIfExists('inventarios');
    }
}
