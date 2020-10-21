<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = [
        'slug',
        'proveedor',
        'encargado',
        'direccion',
        'descripcion',
        'telefono'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
        'deleted_at' => 'datetime:d-m-Y h:i:s'
    ];
}
