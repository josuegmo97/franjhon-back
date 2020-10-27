<?php

namespace App\Http\Controllers;

use App\Dolar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DolarController extends Controller
{
    public function index()
    {
        $info = $this->dateInfo();

        $dolar = Dolar::select('value', 'type', 'sabado_domingo', 'created_at')
                    ->orderBy("id", 'desc')
                    ->first();

        $dolar_dia = Carbon::parse($dolar->created_at->format('Y-m-d'))->format('l');

        // Valido el dia en que se registro ese dolar, con el dia actual
        // Valido la hora en que se registro el dolar con la hora actual
        if($dolar_dia == $info['dia_actual'] && $dolar->type == $info['type']){
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

        if($data['dia_actual'] == 'Sunday' || $data['dia_actual'] == 'Saturday'){
            $data['fin_de_semana'] = true;
        }else{
            $data['fin_de_semana'] = false;
        }

        return $data;
    }
}
