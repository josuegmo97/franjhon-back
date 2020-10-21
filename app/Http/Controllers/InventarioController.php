<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index($slug = null)
    {


        return $this->showResponse(Inventario::where("articulo_id", $slug)->orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'articulo_id' => 'required',

            'cantidad' => 'required|integer', // Cantidad a registrar en el inventario
    
            'pcu_usd' => 'required', // precio compra unitario usd
            'pcu_ves' => 'required', // precio compra unitario veses
            'valor_inicial_usd' => 'required', // valor al principio del invientario es decir cuando se compra
            'valor_inicial_ves' => 'required', // valor al principio del invientario es decir cuando se compra
    
            'pvu_usd' => 'required', // precio venta unitario usd
            'pvu_ves' => 'required', // precio venta unitario bolivares
            'valor_final_usd' => 'required', // valor al final de la venta, es decir la ganancia
            'valor_final_ves' => 'required' // valor al final de la venta, es decir la ganancia
        ]);
        
        $articulo = Articulo::where("slug", $request->articulo_id)->first();

        if(!$articulo){
            return $this->errorResponse("Articulo no existente");
        }

        $inventario = Inventario::create([
            'articulo_id' => $request->articulo_id,
            'cantidad' => $request->cantidad,
    
            'pcu_usd' => $request->pcu_usd,
            'pcu_ves' => $request->pcu_ves,
            'valor_inicial_usd' => $request->valor_inicial_usd,
            'valor_inicial_ves' => $request->valor_inicial_ves ,
    
            'pvu_usd' => $request->pvu_usd ,
            'pvu_ves' => $request->pvu_ves ,
            'valor_final_usd' => $request->valor_final_usd,
            'valor_final_ves' => $request->valor_final_ves        
        ]);

        $articulo->entradas = $articulo->entradas + $inventario->cantidad;
        $articulo->existencia = $articulo->existencia + $inventario->cantidad;
        $articulo->update();

        return $this->showResponse("Lote creado con exito");
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'articulo_id' => 'required'
        ]);

        $articulo = Articulo::where("slug", $request->articulo_id)->first();

        if(!$articulo){
            return $this->errorResponse("Articulo no existente");
        }

        $inventario = Inventario::find($request->id);

        if(!$inventario){
            return $this->errorResponse("Este lote no existe.");
        }

        $articulo->entradas = $articulo->entradas - $inventario->cantidad;
        $articulo->existencia = $articulo->existencia - $inventario->cantidad;
        $articulo->update();

        $inventario->delete();

        return $this->showResponse("Lote eliminado con exito");

    }
}
