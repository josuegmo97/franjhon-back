<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteArticulo;
use App\ClienteArticuloDeuda;
use Illuminate\Http\Request;

class ClienteArticuloDeudaController extends Controller
{
    public function verDeudas($id, $test = false)
    {
        $art = ClienteArticulo::find($id);
        $data['deudas'] = ClienteArticuloDeuda::where("cliente_articulo_id", $id)->get();
        $data['monto'] = $art->cantidad *  $art->deuda_usd;

        foreach($data['deudas'] as $m)
        {
            $data['monto'] -= $m->cantidad_usd;
        }

       //dd($data['deudas']);

        if($test == true){
            return $data;
        }

        return response()->json($data);
    }

    public function registrarCredito($id, $monto_usd)
    {
        $deuda = $this->verDeudas($id, true)['monto'] - $monto_usd;

        if($deuda < 0){
            return $this->errorResponse("El monto a pagar es mayor a la deuda");
        }

        ClienteArticuloDeuda::create([
            'cliente_articulo_id' => $id,
            'cantidad_usd' => $monto_usd
        ]);

        $cedula = ClienteArticulo::select('cliente_id')->find($id);
        $cliente = Cliente::where("cedula", $cedula->cliente_id)->first();
        $cliente->deuda_total-=$monto_usd;
        $cliente->update();

        $this->verificarStatus($id);

        return;
    }

    public function delete(Request $request)
    {
        $request->validate(['id' => 'required']);

        $rmv = ClienteArticuloDeuda::where("cliente_articulo_id", $request->id)->first();
        $cedula = ClienteArticulo::select('cliente_id')->find($request->id);
        $cliente = Cliente::where("cedula", $cedula->cliente_id)->first();
        $cliente->deuda_total+=$rmv->cantidad_usd;
        $cliente->update();
        $rmv->delete();

        $this->verificarStatus($request->id);


        return $this->showResponse("Elimnado con exito");
    }

    public function agregarCredito(Request $request)
    {
        $request->validate(['id' => 'required', 'cantidad' => 'required|numeric']);

        return $this->registrarCredito($request->id, $request->cantidad);
    }

    private function verificarStatus($id)
    {
        $deudas = ClienteArticuloDeuda::where("cliente_articulo_id", $id)->get();
        $inv = ClienteArticulo::find($id);


        $calculo = 0;

        foreach($deudas as $d)
        {
            $calculo += $d->cantidad_usd;
        }


        if($calculo >= ($inv->cantidad *  $inv->deuda_usd))
        {
            $inv->lote_pagado = 1;
        }else{
            $inv->lote_pagado = 0;
        }

        $inv->update();
        return;
    }
}
