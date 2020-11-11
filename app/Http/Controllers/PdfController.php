<?php

namespace App\Http\Controllers;

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

        return view('pdf/test2');
        return;
    }
}