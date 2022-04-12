<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FechaController;
use App\Http\Controllers\TipoProductoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DetallePedidoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/pedidos/save',[DetallePedidoController::class,'store']);

/**
 * Rutas para la fecha
 */

Route::get('/fechas/all',[FechaController::class,'show']);
Route::post('/fechas/save',[FechaController::class,'store']);

/**
 * Rutas para los productos
 */

//Route::get('/productos',[ProductoController::class,'index'])->name('productos');
Route::post('/productos/save',[ProductoController::class,'store']);

/**
 * Rutas para los tipos de productos
 */

Route::get('/tipoproductos/all',[TipoProductoController::class,'show']);
Route::post('/tipoproductos/save',[TipoProductoController::class,'store']);
Route::put('/tipoproductos/update',[TipoProductoController::class,'update']);


