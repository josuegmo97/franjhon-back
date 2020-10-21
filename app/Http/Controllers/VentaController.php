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
            'perdida' => 'required'
        ]);

        
        $articulo = Articulo::where("id", $request->id)->first();
        
        if($request->cantidad > $articulo->existencia){
            return $this->errorResponse("La cantidad es mayor a la existencia");
        }

        Venta::create([
            'puv_ves' => $request->ves,
            'puv_usd' => $request->usd,
            'perdida' => $request->perdida,
            'cantidad' => $request->cantidad,
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
