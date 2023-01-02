<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use LDAP\Result;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $errors = 0;
            DB::beginTransaction();

            //Registrando el cliente
            $cliente = new Cliente();
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            if ($cliente->save() <= 0) {
                $errors++;
            }

            $orden = $request->detallePedido;
            foreach ($orden as $key => $v) {
                //Registrando el pedido
                $pedido = new Pedido();
                $pedido->cantidad = $v['ct'];
                $pedido->fecha = $request->fecha;
                $pedido->hora = $request->hora;
                $pedido->estado = "P";
                $pedido->total = $v['tl'];
                if ($pedido->save() <= 0) {
                    $errors++;
                }

                //Haciendo la relacion con el id de pedidos, el id de productos y el id del cliente
                $detalle = new DetallePedido();
                $detalle->producto_id = $v['pd'];
                $detalle->cliente_id = $cliente->id;
                $detalle->pedido_id = $pedido->id;
                if ($detalle->save() <= 0) {
                    $errors++;
                }
            }

            //consultando si hubieron errores
            if ($errors == 0) {
                DB::commit();
                return response()->json(['status' => 'OK', 'data' => $detalle], 201);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'FAIL', 'data' => $detalle], 209);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $detalle = DetallePedido::join('clientes', 'detalle_pedidos.cliente_id', '=', 'clientes.id')
                ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
                ->select('detalle_pedidos.id', 'clientes.id as clienteId', 'detalle_pedidos.producto_id', 'pedidos.id as pedidoId', 'clientes.nombre as cliente', 'clientes.telefono', 'productos.nombre as producto', 'pedidos.cantidad', 'pedidos.total', 'pedidos.fecha', 'pedidos.hora', 'pedidos.estado')
                ->orderBy('id', 'desc')->get();
            return $detalle;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Se actualiza el cliente
     */
    public function updateCliente(Request $request)
    {

        try {
            $cliente = Cliente::findOrFail($request->id);
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            if ($cliente->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $cliente], 202);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Se actualiza el pedido
     */
    public function updatePedido(Request $request)
    {

        try {
            $errors = 0;
            DB::beginTransaction();

            foreach ($request->pedido as $key => $value) {
                $pedido = Pedido::findOrFail($value['pedidoId']);
                $pedido->cantidad = $value['cantidad'];
                $producto = Producto::findOrFail($value['producto_id']);
                $pedido->total = $producto->precio * $value['cantidad'];
                if ($pedido->save() <= 0) {
                    $errors++;
                }

                $detalle = DetallePedido::findOrFail($value['id']);
                $detalle->producto_id = $value['producto_id'];
                if ($detalle->save() <= 0) {
                    $errors++;
                }
            }

            //consultando si hubieron errores
            if ($errors == 0) {
                DB::commit();
                return response()->json(['status' => 'ok'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'fail'], 209);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Metodo para cambiar el estado del pedido
     * 
     * E = Entregado
     * P = Pendiente
     * C = Cancelado
     */
    public function cambiarEstado(Request $request)
    {
        DB::beginTransaction();
        $errors = 0;
        try {
            foreach ($request->pedido as $key => $pedidoObj) {
                $pedido = Pedido::findOrFail($pedidoObj['id']);
                $pedido->estado = $request->estado;
                if ($pedido->save() < 1) $errors++;
            }
            if ($errors === 0) DB::commit();
            else DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Metodo para filtrar los pedidos por su estado
     * 
     * E = Entregado
     * P = Pendiente
     * C = Cancelado
     * 
     */
    public function filterState(Request $request)
    {
        $estado = $request->state;
        try {

            if ($estado === "P") {
                $clientes = DetallePedido::join('clientes', 'detalle_pedidos.cliente_id', '=', 'clientes.id')
                    ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                    ->select('clientes.id', 'clientes.nombre', 'clientes.telefono')
                    ->groupBy('clientes.id', 'clientes.nombre', 'clientes.telefono')
                    ->where('pedidos.estado', '=', 'P')
                    ->orderBy('id', 'desc')
                    ->get();

                $ordenesList = [];
                $total = 0.0;

                for ($i = 0; $i < $clientes->count(); $i++) {

                    $pedidos = DetallePedido::join('clientes', 'detalle_pedidos.cliente_id', '=', 'clientes.id')
                        ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                        ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
                        ->select('detalle_pedidos.id', 'clientes.id as clienteId', 'detalle_pedidos.producto_id', 'pedidos.id as pedidoId', 'clientes.telefono', 'productos.nombre as producto', 'pedidos.cantidad', 'pedidos.total', 'pedidos.fecha', 'pedidos.hora', 'pedidos.estado')
                        ->where('pedidos.estado', '=', $estado)
                        ->where('clientes.id', $clientes[$i]->id)
                        ->orderBy('id', 'asc')->get();

                    $clientePedidos = [];

                    for ($l = 0; $l < $pedidos->count(); $l++) {
                        $total += $pedidos[$l]->total;
                        array_push($clientePedidos, $pedidos[$l]);
                    }

                    array_push($ordenesList, [
                        "id" => $clientes[$i]->id,
                        "cliente" => $clientes[$i]->nombre,
                        "telefono" => $clientes[$i]->telefono,
                        "total" => number_format((float)$total, 2, '.', ''),
                        "pedidos" => $clientePedidos
                    ]);
                }

                return $ordenesList;
            }
            if ($estado === "E") {
                $pedido = DetallePedido::join('clientes', 'detalle_pedidos.cliente_id', '=', 'clientes.id')
                    ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                    ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
                    ->select('detalle_pedidos.id', 'clientes.id as clienteId', 'detalle_pedidos.producto_id', 'pedidos.id as pedidoId', 'clientes.nombre as cliente', 'clientes.telefono', 'productos.nombre as producto', 'pedidos.cantidad', 'pedidos.total', 'pedidos.fecha', 'pedidos.hora', 'pedidos.estado')
                    ->where('pedidos.estado', '=', $estado)
                    ->orderBy('id', 'asc')->get();
                return $pedido;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function filterCliente(Request $request)
    {
        $cliente = $request->client;
        try {
            $pedido = DetallePedido::join('clientes', 'detalle_pedidos.cliente_id', '=', 'clientes.id')
                ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
                ->select('detalle_pedidos.id', 'clientes.id as clienteId', 'pedidos.id as pedidoId', 'clientes.nombre as cliente', 'clientes.telefono', 'productos.nombre as producto', 'pedidos.cantidad', 'pedidos.total', 'pedidos.fecha', 'pedidos.hora', 'pedidos.estado')
                ->where('clientes.nombre', '=', $cliente)
                ->orderBy('id', 'asc')->get();
            return $pedido;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
