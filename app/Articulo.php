<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class Articulo extends Model
{
    protected $fillable = [
        'proveedor_id',
        'slug', // slug
        'articulo',
        'entradas',
        'salidas',
        'perdidas', // SABOTEO POLICIAS ETC
        'averias',
        'existencia',
    ];

    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    static function getDataPDF()
    {
        $data = [];
        $model = '';
        $articulos = Articulo::get();

        foreach($articulos as $art){

            $cantidad = 0;

            $inventarios = Inventario::where("articulo_id", $art->slug)->orderBy('created_at', 'desc')->get();

            if(count($inventarios) > 0){
                foreach($inventarios as $in){
                    $cantidad+= $in->cantidad;
    
                    if($model != $art->articulo){
                        $model = $art->articulo;

                        // Precio de venta
                        $precio = $in->pvu_usd;
                        $precio_ves = $in->pvu_ves;
                        $precio_venta_final_usd = $in->valor_final_usd;
                        $precio_venta_final_ves = $in->valor_final_ves;
                        // Precio de compra
                        $precio_compra_usd = $in->pcu_usd;
                        $precio_compra_ves = $in->pcu_ves;
                        $precio_compra_inicial_usd = $in->valor_inicial_usd;
                        $precio_compra_inicial_ves = $in->valor_inicial_ves;
                    }
                }
            }else{
                $model = $art->articulo;
                $precio = 0;
                $precio_ves = 0;
                $precio_compra_usd = 0;
                $precio_compra_ves = 0;
                $precio_venta_final_usd = 0;
                $precio_venta_final_ves = 0;
                $precio_compra_inicial_usd = 0;
                $precio_compra_inicial_ves = 0;
            }

            $obj = new stdClass;
            $obj->precio_venta_unitario_usd = $precio;
            //$obj->precio_venta_unitario_ves = $precio_ves;
            $obj->precio_compra_unitario_usd = $precio_compra_usd;
            //$obj->precio_compra_unitario_ves = $precio_compra_ves;
            $obj->precio_venta_final_usd = $precio_venta_final_usd;
            //$obj->precio_venta_final_ves = $precio_venta_final_ves;
            $obj->precio_compra_inicial_usd = $precio_compra_inicial_usd;
            //$obj->precio_compra_inicial_ves = $precio_compra_inicial_ves;
            $obj->cantidad = $cantidad;
            $obj->articulo = $art->articulo;
            $obj->codigo = $art->slug;

            $data[] = $obj;
        }

        return $data;
    }
}
