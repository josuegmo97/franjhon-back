<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Inventario;
use App\Venta;
use Barryvdh\DomPDF\Facade as PDFs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PdfController extends Controller
{
    public function notaDeEntrega($data)
    {
       // $pdf = App::make('dompdf.wrapper');

        $info = [
            'fecha' => $data->fecha,
            'nombre' => $data->nombre,
            'cliente' => $data->cliente,
            'direccion' => $data->direccion,
            'iva' => $data->iva,
            'total_factura' => $data->total_factura,
            'total_operacion' => $data->total_operacion,
            'rif' => $data->rif,
            'nit' => $data->nit,
            'telefono' => $data->telefono,
            'ventas' => $data->ventas,
            'cedula' => $data->cedula,
            'observaciones' => $data->observaciones,
        ];

        $now = Carbon::now()->format("d-m-Y");

        $pdf = PDF::loadView('pdf/test', $info);

        try {
            //code...
            return $pdf->download("Nota de Entrega $now.pdf");
        } catch (\Throwable $th) {
            dd($th);
        }
        return;
    }

    public function notaDeEntregaView()
    {
       // $pdf = App::make('dompdf.wrapper');
        $data_ordenada = $this->array_sort_by(Articulo::getDataPDF(), 'articulo');
        return view('pdf/inventario');
        return;
    }



    public function inventarioProductos()
    {
        $now = Carbon::now()->format("d-m-Y");
        $data_ordenada = $this->array_sort_by(Articulo::getDataPDF(), 'articulo');

        $info = [
            'inventarios' => $data_ordenada,
            'fecha' => $now
        ];

        $pdf = PDF::loadView('pdf/inventario', $info);

        try {
            //code...
            return $pdf->download("Inventario a la fecha $now.pdf");
        } catch (\Throwable $th) {
            dd($th);
        }





    }

    public function array_sort_by($arrIni, $col, $order = SORT_ASC)
    {
        $arrAux = array();
        foreach ($arrIni as $key=> $row)
        {
            $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
            $arrAux[$key] = strtolower($arrAux[$key]);

        }
        
        array_multisort($arrAux, $order, $arrIni);

        $data = [];

        foreach($arrAux as $arr){
            foreach($arrIni as $ini){
                
                if(strtolower($ini->$col) == $arr){
                    //dd($arr, strtolower($ini->$col), $ini);

                    $data[] = $ini;
                }
            }
        }

        return $data;
    }
}