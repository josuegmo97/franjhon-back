<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = [
        'proveedor_id',
        'slug', // slug
        'articulo',
        'entradas',
        'salidas',
        'perdidas', // SABOTEO POLICIAS ETC
        'existencia',
    ];

    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }
}
