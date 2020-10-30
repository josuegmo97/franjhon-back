<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'usd' => 'required',
            'ves' => 'required',
            'id' => 'required',
            'cantidad' => 'required',
            //'perdida' => 'required',

            //'precio_modificado' => 'required' 
        ]);


        if(!$request->perdida){
            $request->perdida = false;
        }
        
        $articulo = Articulo::where("id", $request->id)->first();
        
        if($request->cantidad > $articulo->existencia){
            return $this->errorResponse("La cantidad es mayor a la existencia");
        }

        if($request->precio_modificado){
            $request->perdida = false;
        }else{
            $request->precio_modificado = false;
            $request->vesOriginal = null;
            $request->usdOriginal = null;
        }

        Venta::create([
            'puv_ves' => $request->ves,
            'puv_usd' => $request->usd,
            'puv_ves_original' => $request->vesOriginal,
            'puv_usd_original' => $request->usdOriginal,
            'perdida' => $request->perdida,
            'cantidad' => $request->cantidad,
            'precio_modificado' => $request->precio_modificado,
            'articulo_id' => $articulo->slug
        ]);


        if($request->perdida == true){
            $articulo->perdidas = $articulo->perdidas + $request->cantidad;
        }else{
            $articulo->salidas = $articulo->salidas + $request->cantidad;
        }
        $articulo->existencia = $articulo->existencia - $request->cantidad;

        $articulo->update();

        return $this->showResponse("Venta Exitosa");
    }

}
