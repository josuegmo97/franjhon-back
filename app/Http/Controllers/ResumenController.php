<?php

namespace App\Http\Controllers;

use App\Inventario;
use App\InventarioDeuda;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class ResumenController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();
        $ventas_hoy = Venta::whereDay('created_at',$hoy->format("d"))->whereMonth('created_at',$hoy->format('m'))->whereYear('created_at',$hoy->format("Y"))->count();

        $obj = new stdClass;
        $obj->ventas = $ventas_hoy;
        $obj->deudas = $this->deudas();

        return $this->showResponse($obj);
    }

    private function deudas()
    {
        $inv = Inventario::where('lote_pagado', false)->get();
        $total_deuda = 0;

        foreach($inv as $i){

            $total_deuda+=$i->valor_inicial_usd;
            $deudas = InventarioDeuda::where("inventario_id", $i->id)->get();

            if(count($deudas) > 0){
                foreach($deudas as $d){
                    $total_deuda-= $d->cantidad_usd;
                }
            }
        }

        return $total_deuda;
    }
}