<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteArticuloDeuda extends Model
{
    protected $fillable = [
        'cliente_articulo_id',
        'cantidad_usd'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y'
    ];
}
