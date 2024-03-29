<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('index');
});



/*Route::get('/pdf', 'PdfController@test');*/
Route::get('/pdfM', 'PdfController@notaDeEntregaView');
//Route::get('/pdf', 'PdfController@inventarioProductos');



Route::get( '/{path?}', function(){
    return view('index');
} )->where('path', '.*');