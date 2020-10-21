<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function errorResponse($msg, $code = 422)
    {
        return response()->json($msg, $code);
    }

    public function showResponse($msg)
    {
        return response()->json($msg, 200);
    }

    public function slug($id, $name){

        if($id > 0 && $id < 10){
            $id ="0$id";
        }

        $letter = $name[0];

        if(isset($name[1])){
            $letter.=$name[1];
        }else{
            $letter.=$name[0];
        }

        if(isset($name[2])){
            $letter.=$name[2];
        }else if(isset($name[1])){
            $letter.=$name[1];
        }else{
            $letter.=$name[0];
        }

        //$slug = "$id-".strtoupper($letter);
        $slug = strtoupper($letter) . "-$id";

        return $slug;

    }
}
