<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteArticulo extends Model
{
    protected $fillable = [
        'articulo_id',
        'cliente_id',
        'cantidad',
        'deuda_usd',
        'lote_pagado'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y'
    ];
}
