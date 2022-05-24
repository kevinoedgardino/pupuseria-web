<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TipoProductoController;
use App\Http\Controllers\IndexerController;
use App\Http\Controllers\PDFController;

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
    return view('ordenar');
});

/**
 * Rutas de los indexadores
 */

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/ordenar', [App\Http\Controllers\IndexerController::class, 'indexOrdenes'])->name('ordenar');
Route::get('/pedidos', [App\Http\Controllers\IndexerController::class, 'indexPedidos'])->name('pedidos');
Route::get('/panel-cocina', [App\Http\Controllers\IndexerController::class, 'indexCocina'])->name('panel-cocina');
Route::get('/panel-control', [App\Http\Controllers\IndexerController::class, 'indexControl'])->name('panel-control');

/**
 * Ruta para los productos
 */

Route::get('/productos/all',[ProductoController::class,'show']);
Route::get('/productos/filter-type',[ProductoController::class,'filterStateType']);
Route::get('/productos/filter',[ProductoController::class,'filterState']);
Route::post('/productos/save',[ProductoController::class,'store']);
Route::put('/productos/update',[ProductoController::class,'update']);
Route::put('/productos/change',[ProductoController::class,'cambiarEstado']);

/**
 * Rutas para los tipos de productos
 */

Route::get('/tipoproductos/all',[TipoProductoController::class,'show']);

/**
 * Rutas para los detalles de los pedidos
 */

Route::get('/pedidos/all',[DetallePedidoController::class,'show']);
Route::post('/pedidos/save',[DetallePedidoController::class,'store']);
Route::put('/pedidos/update-cliente',[DetallePedidoController::class,'updateCliente']);
Route::put('/pedidos/update-pedido',[DetallePedidoController::class,'updatePedido']);
Route::put('/pedidos/change',[DetallePedidoController::class,'cambiarEstado']);
Route::get('/pedidos/filter',[DetallePedidoController::class,'filterState']);
Route::get('/pedidos/filter-cliente',[DetallePedidoController::class,'filterCliente']);

/**
 * Rutas para el PDF
 */
Route::get('/pedidos/reporte',[PDFController::class, 'pdfPedidos'])->name('pdfPedidos');
Route::get('/pedidos/reportefecha',[PDFController::class, 'pdfFechaPedidos'])->name('pdfFechaPedidos');

Auth::routes();