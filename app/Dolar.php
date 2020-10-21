<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dolar extends Model
{
    protected $fillable = [
        'value',
        'type' , // mañana y tarde
        'sabado_domingo'
    ];

    protected $casts = [
        //'created_at' => 'datetime:Y-m-d'
    ];
}
