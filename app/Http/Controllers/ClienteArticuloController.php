<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteArticulo;
use Illuminate\Http\Request;

class ClienteArticuloController extends Controller
{
    public function crear_deuda_articulo($slug, $cedula, $deuda_usd, $cantidad)
    {
        ClienteArticulo::create([
            'articulo_id' => $slug,
            'cliente_id' => $cedula,
            'deuda_usd' => $deuda_usd,
            'cantidad' => $cantidad,
            'lote_pagado' => false
        ]);

        $art = Cliente::where("cedula", $cedula)->first();
        $art->deuda_total+= $deuda_usd*$cantidad;
        $art->update();

        return "success";
    }

    public function lista_deuda($cedula = null)
    {
        if($cedula){
            $cliente = ClienteArticulo::where("cliente_id", $this->limpiar_cedula($cedula))->get();
        }else{
            $cliente = ClienteArticulo::where("lote_pagado", false)->get();
        }

        return $this->showResponse($cliente);
    }
}
