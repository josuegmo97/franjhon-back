<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Cliente;
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
        
        // solo si es cliente
        if($request->cliente == true){
            $request->validate(['cedula' => 'required|numeric']);
            $cliente = Cliente::where('cedula', $this->limpiar_cedula($request->cedula))->first();
            if(!$cliente){
                return $this->errorResponse("Cliente invalido");
            }
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

        if($request->cliente == true){
            $cliente_deuda = new ClienteArticuloController;
            $cliente_deuda->crear_deuda_articulo($articulo->slug, $this->limpiar_cedula($request->cedula), $request->usd, $request->cantidad);
        }

        if($request->perdida == true){
            $articulo->perdidas = $articulo->perdidas + $request->cantidad;
        }else{
            $articulo->salidas = $articulo->salidas + $request->cantidad;
        }
        $articulo->existencia = $articulo->existencia - $request->cantidad;

        $articulo->update();

        return $this->showResponse("Venta Exitosa");
    }

    public function devolucio_averia(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'cantidadAveria' => 'nullable|integer',
            'cantidadDevolucion' => 'nullable|integer'
        ]);

        $venta = Venta::find($request->id);
        $articulo = Articulo::where("slug", $venta->articulo_id)->first();

        if($request->cantidadAveria != 0 && $request->cantidadAveria != ''){
            $articulo->averias+=$request->cantidadAveria;
            $articulo->salidas-=$request->cantidadAveria;
            $articulo->update();
        }

        if($request->cantidadDevolucion != 0 && $request->cantidadDevolucion != ''){

            if($request->cantidadDevolucion == $venta->cantidad){
                $venta->delete();
            }else{
                $venta->cantidad-=$request->cantidadDevolucion;
                $venta->update();
            }
        }

        return "Success";
    }

}
