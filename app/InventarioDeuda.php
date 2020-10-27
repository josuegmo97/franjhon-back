<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioDeuda extends Model
{
    protected $fillable = [
        'inventario_id',
        'cantidad_usd', // Cantidad a registrar en el lote
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
