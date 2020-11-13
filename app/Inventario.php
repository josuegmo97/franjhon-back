<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'articulo_id',

        'cantidad', // Cantidad a registrar en el inventario

        'pcu_usd', // precio compra unitario dolar
        'pcu_ves', // precio compra unitario bolivares
        'valor_inicial_usd', // valor al principio del invientario es decir cuando se compra
        'valor_inicial_ves', // valor al principio del invientario es decir cuando se compra

        'pvu_usd', // precio venta unitario dolar
        'pvu_ves', // precio venta unitario bolivares
        'valor_final_usd', // valor al final de la venta, es decir la ganancia
        'valor_final_ves', // valor al final de la venta, es decir la ganancia

        'lote_pagado',
        'tipo_pago'
    ];

    static function dataToPDF()
    {
        return Inventario::select(
                    'inventarios.articulo_id',
                    'inventarios.cantidad',
                    'inventarios.pvu_usd',
                    'articulos.articulo'
                )
                ->join('articulos', 'articulos.slug', 'inventarios.articulo_id')
                ->get();
    }
}
