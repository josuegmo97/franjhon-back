<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Inventario;
use Illuminate\Http\Request;

class InventarioController extends InventarioDeudaController
{
    public function index($slug = null)
    {

        $data = Inventario::select(
            'inventarios.articulo_id',
            'inventarios.cantidad', // Cantidad a registrar en el inventario
            'inventarios.pcu_usd', // precio compra unitario dolar
            'inventarios.pcu_ves', // precio compra unitario bolivares
            'inventarios.valor_inicial_usd', // valor al principio del invientario es decir cuando se compra
            'inventarios.valor_inicial_ves', // valor al principio del invientario es decir cuando se compra

            'inventarios.pvu_usd', // precio venta unitario dolar
            'inventarios.pvu_ves', // precio venta unitario bolivares
            'inventarios.valor_final_usd', // valor al final de la venta, es decir la ganancia
            'inventarios.valor_final_ves', // valor al final de la venta, es decir la ganancia

            'inventarios.lote_pagado',
            'inventarios.tipo_pago',
            'inventarios.id',
            'inventarios.created_at',
            'articulos.articulo'
        )
        ->where("inventarios.articulo_id", $slug)->orderBy('inventarios.id', 'desc')
        ->join('articulos', 'articulos.slug', 'inventarios.articulo_id')
        ->get();


        return $this->showResponse($data);
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
            'valor_final_ves' => 'required', // valor al final de la venta, es decir la ganancia

            'lote_pagado' => 'required'
        ]);

        $tipo_pago = 'Contado';

        if($request->lote_pagado == 0){
            $request->validate([
                'pago_inicial' => 'required'
            ]);
            $tipo_pago = 'Credito';
        }

        
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
            'valor_final_ves' => $request->valor_final_ves,   
            'lote_pagado' => $request->lote_pagado,        
            'tipo_pago' => $tipo_pago        
        ]);

        if($request->lote_pagado == 0 && $request->pago_inicial > 0){
            $this->registrarCredito($inventario->id, $request->pago_inicial);
        }

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
