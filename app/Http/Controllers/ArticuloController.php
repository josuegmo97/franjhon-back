<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Inventario;
use App\Proveedor;
use Illuminate\Http\Request;
use stdClass;

class ArticuloController extends Controller
{
    /*
        'proveedor_id',
        'slug',
        'articulo',
        'entradas',
        'salidas',
        'perdidas', // SABOTEO POLICIAS ETC
        'existencia',
    */

    public function edit(Request $request)
    {
        $request->validate([
            'slug' => 'required',
        ]);

        if($request->has('articulo')){

            $ar = Articulo::where("slug", $request->slug)->first();

            $ar->articulo = $request->articulo;
            $ar->slug = $this->slug($ar->id, $ar->articulo);
            $ar->update();

            return $this->showResponse("Editado");
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required',
            'articulo' => 'required',
        ]);

        if(!Proveedor::where("slug", $request->proveedor_id)->exists()){
            return $this->errorResponse("Proveedor no encontrado");
        }

        $articulo = Articulo::create([
                        'proveedor_id' => $request->proveedor_id,
                        'articulo' => $request->articulo,
                        'entradas' => 0,
                        'salidas' => 0,
                        'perdidas' => 0, // SABOTEO POLICIAS ETC
                        'existencia' => 0,
                        'averias' => 0,
                        'slug' => null
                    ]);
        
        $articulo->slug = $this->slug($articulo->id, $articulo->articulo);
        $articulo->update();

        return $this->showResponse("Articulo creado con exito");
    }

    public function index($slug = null)
    {
        if(!$slug){
            return $this->showResponse(Articulo::orderBy('articulo', 'asc')->get());
        }
        return $this->showResponse(Articulo::where("proveedor_id", $slug)->orderBy('id', 'desc')->get());
    }

    public function indexVentas()
    {
        $articulos = Articulo::where('existencia', ">", 0)->select('slug', 'existencia', 'articulo', 'id')->get();

        foreach($articulos as $ar)
        {

            $inv = Inventario::where("articulo_id", $ar->slug)->orderBy('created_at', 'desc')->first();

                $ar->pvu_usd = $inv->pvu_usd;
                $ar->pvu_ves = $inv->pvu_ves;
        }

        return $articulos;
    }
}
