<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    const select_ventas = ['ventas.id', 'ventas.created_at', 'ventas.articulo_id', 'ventas.cantidad', 'ventas.precio_modificado', 'ventas.puv_usd', 'ventas.puv_ves', 'ventas.perdida', 'articulos.proveedor_id'];
    const doc_ventas = ['ventas.articulo_id', 'ventas.cantidad', 'ventas.precio_modificado', 'ventas.puv_ves', 'ventas.perdida', 'articulos.proveedor_id', 'articulos.articulo', 'puv_ves_original'];

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
                ->orderBy('ventas.created_at', 'desc')
                ->get();
    }

    static function getAll(){
        return Venta::select(Venta::select_ventas)
                ->join('articulos', 'articulos.slug', 'ventas.articulo_id')
                ->orderBy('ventas.created_at', 'desc')
                ->get();
    }

    static function getWhereDate($inicio, $final){

        $from = date($inicio . ' 00:00:00', time()); //need a space after dates.
        $to = date($final . ' 23:59:00', time());

        return Venta::select(Venta::select_ventas)
                ->whereBetween('ventas.created_at', [$from ,$to])
                ->leftJoin('articulos', 'articulos.slug', 'ventas.articulo_id')
                ->orderBy('ventas.created_at', 'desc')
                ->get();
    }

    static function notaEntrega($ids){
        return Venta::select(Venta::doc_ventas)
                ->whereIn('ventas.id', $ids)
                ->join('articulos', 'articulos.slug', 'ventas.articulo_id')
                ->orderBy('ventas.created_at', 'desc')
                ->get();
    }
}
