<?php

//CORS
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
//CORS

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});


Route::group(['middleware' => 'auth:api'], function() {

    
    Route::group(["prefix" => "proveedor"], function(){
        Route::post('/', 'ProveedorController@store');
        Route::get('/', 'ProveedorController@index');
        Route::get('/show/{id}', 'ProveedorController@show');
        Route::post('/edit', 'ProveedorController@edit');
        Route::get('/delete/{id}', 'ProveedorController@delete');
    });

    Route::group(["prefix" => "cliente"], function(){
        Route::post('/', 'ClienteController@store');
        Route::get('/', 'ClienteController@index');
        Route::get('/show/{cedula?}', 'ClienteController@verificar_cliente');
        /*Route::post('/edit', 'ClienteController@edit');
        Route::get('/delete/{id}', 'ClienteController@delete');*/

        Route::get('/articulo/{cedula?}', 'ClienteArticuloController@lista_deuda');

        // DEUDA
        Route::get('/pendiente/show/{id?}', 'ClienteArticuloDeudaController@verDeudas');
        Route::post('/pendiente/delete', 'ClienteArticuloDeudaController@delete');
        Route::post('/pendiente/agregar', 'ClienteArticuloDeudaController@agregarCredito');
        
    });
    
    Route::group(["prefix" => "articulo"], function(){
        Route::post('/', 'ArticuloController@store');
        Route::get('/slug/{slug?}', 'ArticuloController@index');
        Route::get('/ventas', 'ArticuloController@indexVentas');
        Route::post('/edit', 'ArticuloController@edit');
        //Route::get('/delete/{id}', 'ArticuloController@delete');
        //Route::get('/show/{id}', 'ArticuloController@show');
    });
    
    Route::group(["prefix" => "inventario"], function(){
        Route::post('/', 'InventarioController@store');
        Route::get('/slug/{slug?}', 'InventarioController@index');
        Route::post('/edit', 'InventarioController@edit');
        Route::post('/delete', 'InventarioController@delete');
        //Route::get('/show/{id}', 'InventarioController@show');

        // DEUDA
        Route::get('/pendiente/show/{id?}', 'InventarioDeudaController@verDeudas');
        Route::post('/pendiente/delete', 'InventarioDeudaController@delete');
        Route::post('/pendiente/agregar', 'InventarioDeudaController@agregarCredito');
    });
    
    Route::group(["prefix" => "venta"], function(){
        Route::post('/', 'VentaController@store');
        Route::post('/consulta', 'VentaController@consulta');
        Route::post('/devolucion/averia', 'VentaController@devolucio_averia');
        
        //Route::get('/slug/{slug?}', 'VentaController@index');
        //Route::post('/edit', 'VentaController@edit');
        //Route::post('/delete', 'VentaController@delete');
        //Route::get('/show/{id}', 'VentaController@show');
    });

    Route::group(["prefix" => "dolar"], function(){
        Route::post('/', 'DolarController@store');
        Route::get('/', 'DolarController@index');
        Route::get('/historico', 'DolarController@historico');
    });

    Route::group(["prefix" => "resumen"], function(){
        Route::get('/', 'ResumenController@index');
    });

    Route::group(["prefix" => "consulta"], function(){
        Route::post('/', 'ConsultaController@index');
    });

    

    
});

Route::group(["prefix" => "documento"], function(){
    Route::post('/', 'FacturaController@notaEntrega');
    Route::post('/inventario', 'PdfController@inventarioProductos');
});




Route::post('/b', 'BalanceController@balance');



Route::group(["prefix" => "dolar"], function(){
    Route::post('/', 'DolarController@store');
    Route::get('/', 'DolarController@index');
});

/*Route::group(["prefix" => "documento"], function(){
    Route::post('/', 'FacturaController@notaEntrega');
});


Route::get('/pdf', 'PdfController@test');*/