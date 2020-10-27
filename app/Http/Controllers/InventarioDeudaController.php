<?php

namespace App\Http\Controllers;

use App\Inventario;
use App\InventarioDeuda;
use Illuminate\Http\Request;

class InventarioDeudaController extends Controller
{
    public function verDeudas($id, $test = false)
    {
        $data['deudas'] = InventarioDeuda::where("inventario_id", $id)->get();
        $data['monto'] = Inventario::find($id)->valor_inicial_usd;

        foreach($data['deudas'] as $m)
        {
            $data['monto'] -= $m->cantidad_usd;
        }

        if($test == true){
            return $data;
        }

        return response()->json($data);
    }

    public function registrarCredito($id, $monto_usd)
    {
        $deuda = $this->verDeudas($id, true)['monto'] - $monto_usd;
//$deuda = $this->verDeudas($id, true)['monto'];

        //dd($deuda, $this->verDeudas($id, true)['monto'] , $monto_usd);

        if($deuda < 0){
            return $this->errorResponse("El monto a pagar es mayor a la deuda");
        }

        InventarioDeuda::create([
            'inventario_id' => $id,
            'cantidad_usd' => $monto_usd
        ]);

        $this->verificarStatus($id);

        return;
    }

    public function delete(Request $request)
    {
        $request->validate(['id' => 'required']);

        InventarioDeuda::find($request->id)->delete();

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
        $deudas = InventarioDeuda::where("inventario_id", $id)->get();
        $inv = Inventario::find($id);

        $calculo = 0;

        foreach($deudas as $d)
        {
            $calculo += $d->cantidad_usd;
        }

        if($calculo >= $inv->valor_inicial_usd)
        {
            $inv->lote_pagado = 1;
        }else{
            $inv->lote_pagado = 0;
        }

        $inv->update();
        return;
    }
}
