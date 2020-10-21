<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('proveedor_id')->required();
            $table->string("slug")->nullable()->unique();
            $table->string("articulo")->required();
            $table->integer('entradas')->nullable();
            $table->integer('salidas')->nullable();
            $table->integer('perdidas')->nullable();
            $table->integer('existencia')->nullable();
            $table->foreign('proveedor_id')->references("slug")->on('proveedors');
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
        Schema::dropIfExists('articulos');
    }
}
