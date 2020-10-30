<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class ProveedorController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $data = Proveedor::orderBy('id', 'desc')->get();
        
        return $this->showResponse($data);
    }

    public function show($id)
    {
        $pro = Proveedor::where("slug", $id)->first();

        if(!$pro){
            return $this->errorResponse("Proveedor no encontrado");
        }

        $obj = new stdClass;
        $obj->proveedor = $pro;
        $obj->extra = $this->extraInfo($id);

        return $this->showResponse($obj);
    }

    public function store(Request $request)
    {
        $request->validate(['proveedor' => 'required']);

        $pro = Proveedor::create([
            'slug' => null,
            'proveedor' => $request->proveedor,
            'encargado' => ucwords($request->encargado),
            'direccion' => ucwords($request->direccion),
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono
        ]);

        $pro->slug = $this->slug($pro->id, $pro->proveedor);
        $pro->update();

        return $this->showResponse("Proveedor registrado con exito");
    }

    public function edit(Request $request)
    {
        $request->validate(["id" => "required|integer"]);

        $pro = Proveedor::find($request->id);

        if(!$pro){
            return $this->errorResponse("Proveedor no encontrado");
        }

        if($request->has("proveedor")){
            $pro->proveedor = $request->proveedor;
        }

        if($request->has("encargado")){
            $pro->encargado = ucwords($request->encargado);
        }

        if($request->has("direccion")){
            $pro->direccion = ucwords($request->direccion);
        }

        if($request->has("telefono")){
            $pro->telefono = $request->telefono;
        }

        if($request->has("descripcion")){
            $pro->descripcion = $request->descripcion;
        }

        $pro->update();

        return $this->showResponse("Proveedor editado con exito");
    }

    public function delete($id)
    {
        $pro = Proveedor::find($id);

        if(!$pro){
            return $this->errorResponse("Proveedor no encontrado");
        }

        // $pro->delete(); // No seria lo correcto eliminarlo

        return $this->showResponse("Proveedor eliminado con exito");
    }

    private function extraInfo($id){

        $articulos = Articulo::where('proveedor_id', $id)->select('id')->count();

        $inventario_deuda = Articulo::where('articulos.proveedor_id', $id)
                                ->select('inventarios.id', 'inventarios.articulo_id', 'articulos.slug')
                                ->where('inventarios.lote_pagado', 0)
                                ->leftJoin('inventarios', 'inventarios.articulo_id', 'articulos.slug')
                                ->count();
        

        if($inventario_deuda > 0){
            $deuda = "Posee Deudas";
        } else{
            $deuda = "No Posee Deudas";
        }

        $inversion_total = Articulo::where('articulos.proveedor_id', $id)
                                ->select('inventarios.id', 'inventarios.articulo_id', 'articulos.slug', 'inventarios.valor_inicial_usd')
                                ->leftJoin('inventarios', 'inventarios.articulo_id', 'articulos.slug')
                                ->get();


        $inv = 0 ;

        foreach($inversion_total as $i){
            $inv+= $i->valor_inicial_usd;
        }

        $obj = new stdClass;
        $obj->total_usd_inversion = $inv;
        $obj->articulos = $articulos;
        $obj->deudas = $deuda;

        return $obj;
    }
}
