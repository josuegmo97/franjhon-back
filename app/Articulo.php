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

            foreach($inventarios as $in){
                $cantidad+= $in->cantidad;

                if($model != $art->articulo){
                    $model = $art->articulo;
                    $precio = $in->pvu_usd;
                }
            }

            $obj = new stdClass;
            $obj->precio = $precio;
            $obj->cantidad = $cantidad;
            $obj->articulo = $art->articulo;
            $obj->codigo = $art->slug;

            $data[] = $obj;
        }

        return $data;
    }
}
