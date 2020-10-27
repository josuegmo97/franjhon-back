<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('articulo_id')->required();
            $table->integer('cantidad')->required();
            $table->string("puv_usd")->required();
            $table->string("puv_ves")->required();
            $table->string("puv_usd_original")->nullable();
            $table->string("puv_ves_original")->nullable();
            $table->boolean("perdida")->required();
            $table->boolean("precio_modificado")->required();
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
        Schema::dropIfExists('ventas');
    }
}
