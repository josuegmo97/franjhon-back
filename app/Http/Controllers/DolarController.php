<?php

namespace App\Http\Controllers;

use App\Dolar;
use App\Inventario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DolarController extends Controller
{

    public function index()
    {
        $info = $this->dateInfo();

        /* FUME ASTRAL */
        if($info['type'] == 'sin definir'){
            $dolar = Dolar::select('value', 'type', 'sabado_domingo', 'created_at')
                        ->orderBy('created_at', 'desc')->skip(1)->take(1)
                        ->first();
        }else{
            $dolar = Dolar::select('value', 'type', 'sabado_domingo', 'created_at')
                        ->orderBy("created_at", 'desc')
                        ->first();
        }

        $dolar_dia = Carbon::parse($dolar->created_at->format('Y-m-d'))->format('l');

        // Valido el dia en que se registro ese dolar, con el dia actual
        // Valido la hora en que se registro el dolar con la hora actual
        if($dolar && $dolar_dia == $info['dia_actual'] && $dolar->type == $info['type']){
            return $this->showResponse($dolar->value);
        }else{
            return $this->errorResponse("No se encontro registro");
        }
    }


    public function store(Request $request)
    {
        $request->validate(["value" => 'required']);

        $info = $this->dateInfo();

        Dolar::create([
            'value' => $request->value,
            'type' => $info['type'],
            'sabado_domingo' => $info['fin_de_semana']
        ]);

        $this->actualizar_bolivares_inventario($request->value);

        return $this->showResponse("Dolar actualizado");
    }

    private function dateInfo()
    {
        
        $data['fecha_actual'] = Carbon::now()->format('Y-m-d');
        $data['hora_actual'] = Carbon::now()->format('H:i');
        $data['dia_actual'] = Carbon::parse($data['fecha_actual'])->format('l');

        // Mañana - 9
        if($data['hora_actual'] >= "09:15" && $data['hora_actual'] < "13:15")
        {
            $data['type'] = "manana";
        }else{
            $data['type'] = "tarde";
        }

        /* viejo */
        /*
        if($data['hora_actual'] >= "09:15" && $data['hora_actual'] < "13:15")
        {
            $data['type'] = "manana";
        }else if($data['hora_actual'] >= "13:15" && $data['hora_actual'] <= "23:59"){
            $data['type'] = "tarde";
        }else{
            $data['type'] = "sin definir";
        }
        */

        if($data['dia_actual'] == 'Sunday' || $data['dia_actual'] == 'Saturday'){
            $data['fin_de_semana'] = true;
        }else{
            $data['fin_de_semana'] = false;
        }

        return $data;
    }

    public function historico()
    {
        return Dolar::select("value", "type", "created_at")->orderBy("created_at", 'desc')->get();
    }


    /* esto va en otro lado pero funciona */
    private function actualizar_bolivares_inventario($valor){
        $inventarios = Inventario::select('id', 'cantidad', 'pvu_usd', 'valor_final_ves')->get();

        foreach($inventarios as $i){

            $i->pvu_ves = $valor * $i->pvu_usd;
            $i->valor_final_ves = $i->pvu_ves * $i->cantidad;
            DB::update("update inventarios set pvu_ves = ?, valor_final_ves = ? where id = ?", [$i->pvu_ves, $i->valor_final_ves, $i->id]);

        }
        return;
    }
}
