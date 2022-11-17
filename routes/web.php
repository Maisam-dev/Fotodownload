<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\fotodateienmediaserver;
use \App\Http\Controllers\fototest;
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

/*Route::get('/', function () {
    return view('welcome');
});*/



Route::get('/{art}/{prm}',[fotodateienmediaserver::class,'getfoto']
          )->where('art','download|preview');


Route::get('/fototest/{prm?}',[fototest::class,'fototest']);
Route::post('/fototest',[fototest::class,'fototest']);
