<?php

namespace App\Http\Controllers;

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
}
