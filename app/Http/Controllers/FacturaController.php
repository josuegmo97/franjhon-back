<?php

namespace App\Http\Controllers;

use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class FacturaController extends Controller
{
    public function notaEntrega(Request $request){

        //dd($request->all());
        $request->validate(['ids' => 'required|array']);

        $ventas = Venta::notaEntrega($request->ids);

        $total_factura = 0;

        foreach($ventas as $v){

            /*
                Descuentos
            */
            if($v->precio_modificado == true){
                $v->descuento = $v->puv_ves_original - $v->puv_ves;
                $v->puv_ves = $v->puv_ves_original;
                $v->total = $v->cantidad * ($v->puv_ves - $v->descuento);
            }else{
                $v->total = $v->cantidad * $v->puv_ves;
                $v->descuento = 0;
            }
            
            $total_factura+=$v->total;
        }

        $iva = $total_factura * 0.16;

        $obj = new stdClass;
        $obj->fecha = Carbon::now()->format('d/m/Y');
        $obj->nombre = $request->nombre;
        $obj->cliente = $request->cliente;
        $obj->direccion = $request->direccion;
        $obj->iva = number_format($iva,2);
        $obj->total_factura = number_format($total_factura, 2);
        $obj->total_operacion = number_format($total_factura + $iva, 2);
        $obj->rif = $request->rif;
        $obj->nit = $request->nit;
        $obj->ventas = $ventas;
        $obj->telefono = $request->telefono;
        $obj->cedula = $request->cedula;
        $obj->observaciones = $request->observaciones;

        $pdf = new PdfController;

        return $pdf->notaDeEntrega($obj);
    }
}
// const doc_ventas = ['ventas.articulo_id', 'ventas.cantidad', 'ventas.precio_modificado', 'ventas.puv_ves', 'ventas.perdida', 'articulos.proveedor_id'];

