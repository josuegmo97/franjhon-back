<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'cedula',
        'status',
        'telefono',
        'deuda_total'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y'
    ];
}
