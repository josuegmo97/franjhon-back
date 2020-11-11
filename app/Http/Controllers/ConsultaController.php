<?php

namespace App\Http\Controllers;

use App\Inventario;
use App\InventarioDeuda;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class ConsultaController extends Controller
{

    public function index(Request $request)
    {
        $request->validate(["type" => 'required|in:Dia,Todo,Desde - Hasta', 'model' => 'required']);

        if($request->type == 'FECHA'){
            $request->validate(["desde" => 'required', "hasta" => 'required']);
        }

        $obj = new stdClass;
        $obj->type = $request->type;
        $obj->desde = $request->desde;
        $obj->hasta = $request->hasta;

        if($request->model == 'ventas'){
            return $this->showResponse($this->ventas($obj));
        }

        if($request->model == 'deudas'){
            return $this->showResponse($this->deudas($obj));
        }

        // dd($request->all());

        // return $this->showResponse($this->ventas());
    }

    private function ventas($data)
    {
        if($data->type == 'Dia'){
            return Venta::getDay();
        }

        if($data->type == 'Todo'){
            return Venta::getAll();
        }

        if($data->type == 'Desde - Hasta'){

            return Venta::getWhereDate($data->desde, $data->hasta);
        }
    }

    private function deudas($data)
    {
        $inv = Inventario::select(
                        'inventarios.id',
                        'inventarios.articulo_id',
                        'inventarios.cantidad', // Cantidad a registrar en el inventario
                        'inventarios.pcu_usd', // precio compra unitario dolar
                        'inventarios.pcu_ves', // precio compra unitario bolivares
                        'inventarios.valor_inicial_usd', // valor al principio del invientario es decir cuando se compra
                        'inventarios.valor_inicial_ves', // valor al principio del invientario es decir cuando se compra
                        /*'inventarios.pvu_usd', // precio venta unitario dolar
                        'inventarios.pvu_ves', // precio venta unitario bolivares
                        'inventarios.valor_final_usd', // valor al final de la venta, es decir la ganancia
                        'inventarios.valor_final_ves', */ // valor al final de la venta, es decir la ganancia
                        'inventarios.lote_pagado',
                        'inventarios.created_at',
                        'articulos.articulo',
                        'articulos.proveedor_id'
                    )
                    ->where('inventarios.lote_pagado', false)
                    ->join('articulos', 'articulos.slug', 'inventarios.articulo_id')
                    ->get();
        /*$total_deuda = 0;

        foreach($inv as $i){

            $total_deuda+=$i->valor_inicial_usd;
            $deudas = InventarioDeuda::where("inventario_id", $i->id)->get();

            if(count($deudas) > 0){
                foreach($deudas as $d){
                    $total_deuda-= $d->cantidad_usd;
                }
            }
        }*/

        return $inv;
    }
}
