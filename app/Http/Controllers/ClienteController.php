<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ClienteArticulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->get();

        // Clientes
        /*foreach($clientes as $cliente){

            // Articulo del cliente
            $articulos = ClienteArticulo::where("cliente_id", $cliente->cedula_id)->get();

            if(count($articulos) != 0){

            }

        }*/
        return $clientes;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required', 'cedula' => 'required|numeric'
        ],[
            'nombre.required' => 'La cedula es requerida.',
            'cedula.numeric' => 'La cedula debe ser valor numerico sin puntos ni guiones.',
        ]);

        if ( $validator->fails() )
            return $this->errorResponse($validator->errors()->first());

        if(Cliente::where('cedula', $this->limpiar_cedula($request->cedula))->exists()){
            return $this->errorResponse("Ya existe un cliente con esta cedula");
        }

        Cliente::create([
            'nombre' => ucwords($request->nombre),
            'cedula' => $this->limpiar_cedula($request->cedula),
            'telefono' => $request->telefono
        ]);

        return $this->showResponse("Cliente registrado con exito");
    }

    

    public function verificar_cliente($cedula)
    {
        if(!$cedula){
            return $this->errorResponse("La cedula es obligatoria");
        }

        $cliente = Cliente::select('nombre', 'status', 'cedula')->where("cedula", $this->limpiar_cedula($cedula))->first();

        if(!$cliente){
            return $this->errorResponse("Cliente no registrado.");
        }

        if($cliente->status == false){
            return $this->errorResponse("Cliente inhabilitado.");
        }

        return $this->showResponse($cliente->nombre);
    }
}
