<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\ClienteArticulo;
use App\ClienteArticuloDeuda;
use App\Inventario;
use App\InventarioDeuda;
use App\Venta;
use Illuminate\Http\Request;
use stdClass;

class BalanceController extends Controller
{
    public function balance(Request $request){

        $request->validate(["fecha" => 'required', 'valor' => 'required|numeric' ]);

        $fecha = $this->explodeDate($request->fecha);

        return $this->getInfo($fecha, $request->valor);
    }

    private function getInfo($fecha, $valor)
    {
        $obj = [];
        $obj['cuentas_por_pagar'] = $this->debitos();
        $obj['creditos_a_clientes'] = $this->creditos();
        $obj['inventario'] = $this->inventario();
        $obj['exoneraciones'] = $this->exoneraciones($fecha);
        $obj['descuentos'] = $this->descuentos($fecha);
        $obj['ventas_formales'] = $this->ventas_formales($fecha);
        $obj['averias'] = $this->averias();
        $obj['calculado_a'] = $valor; // BOLIVARES CON QUE SE HIZO EL CALCULO DEL DOLAR


        return $obj;
    }

    private function debitos()
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

        return round($total_deuda,2);
    }

    private function creditos()
    {
        $inv = ClienteArticulo::where('lote_pagado', false)->get();
        $total_deuda = 0;

        foreach($inv as $i){

            $total_deuda+=($i->cantidad * $i->deuda_usd);
            $deudas = ClienteArticuloDeuda::where("cliente_articulo_id", $i->id)->get();

            if(count($deudas) > 0){
                foreach($deudas as $d){
                    $total_deuda-= $d->cantidad_usd;
                }
            }
        }

        return round($total_deuda,2);
    }

    private function averias()
    {
        $articulos = Articulo::where("averias", ">", 0)->select("averias","slug")->get();

        $averias = 0;

        foreach($articulos as $art){

            $ultimo_precio = Inventario::select("pvu_usd")->where("articulo_id", $art->slug)->orderBy("created_at", 'desc')->first();
            $averias+= $ultimo_precio->pvu_usd * $art->averias;
        }

        return round($averias, 2);
    }

    private function inventario()
    {
        $articulos = Articulo::select("existencia", "slug")->where("existencia", ">", 0)->get();
        $total = 0;

        foreach($articulos as $art){

            $inventario = Inventario::where("articulo_id", $art->slug)->select("pvu_usd")->orderBy('created_at', 'desc')->first();
            $total+=$inventario->pvu_usd*$art->existencia;
        }


        return round($total, 2);
    }

    private function exoneraciones($fecha)
    {
        $ventas = Venta::where("perdida", true)->select("perdida", "puv_usd")->whereMonth('created_at', $fecha->mes)->whereYear('created_at', $fecha->ano)->get();

        $perdida = 0;

        foreach($ventas as $v){
            $perdida+=$v->puv_usd;
        }

        return round($perdida,2);
    }

    private function descuentos($fecha)
    {
        $ventas = Venta::where("precio_modificado", true)->select("precio_modificado", "puv_usd", "puv_usd_original")->whereMonth('created_at', $fecha->mes)->whereYear('created_at', $fecha->ano)->get();

        $precio_modificado = 0;

        foreach($ventas as $v){
            $precio_modificado+= $v->puv_usd_original / $v->puv_usd;
        }

        return round($precio_modificado, 2);
    }

    private function ventas_formales($fecha)
    {
        $ventas = Venta::where("perdida", false)->select("perdida","precio_modificado", "puv_usd", "puv_usd_original")->whereMonth('created_at', $fecha->mes)->whereYear('created_at', $fecha->ano)->get();

        $ventas_formales = 0;

        foreach($ventas as $v){
            $ventas_formales+= $v->puv_usd;
        }

        return round($ventas_formales, 2);
    }

    private function explodeDate($data)
    {
        $fecha = explode("-",$data);
        
        $obj = new stdClass;
        $obj->ano = $fecha[0];
        $obj->mes = $fecha[1];

        return $obj;
    }

}
