<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'articulo_id',
        'puv_ves' ,
            'puv_usd' ,
            'perdida' ,
            'cantidad' ,

            'puv_usd_original' ,
            'puv_ves_original' ,
            'precio_modificado' 
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
