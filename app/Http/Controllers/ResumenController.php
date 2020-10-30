<?php

namespace App\Http\Controllers;

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

        return $this->showResponse($obj);
    }

    private function ventas()
    {
        $hoy = Carbon::now();
        $ventas_hoy = Venta::whereDay('created_at',$hoy->format("d"))
                            ->whereMonth('created_at',$hoy->format('m'))
                            ->whereYear('created_at',$hoy->format("Y"))
                            ->count();

        $obj = new stdClass;
        $obj->ventas = $ventas_hoy;

        return $this->showResponse($obj);
    }
}