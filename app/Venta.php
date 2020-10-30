<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    const select_ventas = ['ventas.created_at', 'ventas.articulo_id', 'ventas.cantidad', 'ventas.precio_modificado', 'ventas.puv_usd', 'ventas.puv_ves', 'ventas.perdida', 'articulos.proveedor_id'];

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
        'created_at' => 'datetime:d-m-Y'
    ];

    static function getDay(){
        $hoy = Carbon::now();
        return Venta::select(Venta::select_ventas)
                ->whereDay('ventas.created_at',$hoy->format("d"))
                ->whereMonth('ventas.created_at',$hoy->format('m'))
                ->whereYear('ventas.created_at',$hoy->format("Y"))
                ->join('articulos', 'articulos.slug', 'ventas.articulo_id')
                ->get();
    }

    static function getAll(){
        return Venta::select(Venta::select_ventas)
                ->join('articulos', 'articulos.slug', 'ventas.articulo_id')
                ->get();
    }

    static function getWhereDate($inicio, $final){

        $from = date($inicio . ' 00:00:00', time()); //need a space after dates.
        $to = date($final . ' 23:59:00', time());

        return Venta::select(Venta::select_ventas)
                ->whereBetween('ventas.created_at', [$from ,$to])
                ->leftJoin('articulos', 'articulos.slug', 'ventas.articulo_id')
                ->get();
    }
}
