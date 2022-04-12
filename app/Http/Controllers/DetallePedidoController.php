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
            if ($cliente->save()<=0) {
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
                if ($pedido->save()<=0) {
                    $errors++;
                }

                //Haciendo la relacion con el id de pedidos, el id de productos y el id del cliente
                $detalle = new DetallePedido();
                $detalle->producto_id = $v['pd'];
                $detalle->cliente_id = $cliente->id;
                $detalle->pedido_id = $pedido->id;
                if ($detalle->save()<=0) {
                    $errors++;
                }
            }

            //consultando si hubieron errores
            if ($errors == 0) {
                DB::commit();
                return response()->json(['status'=>'OK','data'=>$detalle],201);
            }
            else {
                DB::rollBack();
                return response()->json(['status'=>'FAIL','data'=>$detalle],209);
            }
        } 
        catch (\Exception $e) {
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
            $detalle = DetallePedido::join('clientes','detalle_pedidos.cliente_id','=','clientes.id')
            ->join('pedidos','detalle_pedidos.pedido_id','=','pedidos.id')
            ->join('productos','detalle_pedidos.producto_id','=','productos.id')
            ->select('detalle_pedidos.id','clientes.id as clienteId','detalle_pedidos.producto_id','pedidos.id as pedidoId','clientes.nombre as cliente','clientes.telefono','productos.nombre as producto','pedidos.cantidad','pedidos.total','pedidos.fecha','pedidos.hora','pedidos.estado')
            ->orderBy('id','asc')->get();
            return $detalle;
        }
        catch (\Exception $e) {
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
            if ($cliente->save()>=1) {
                return response()->json(['status'=>'OK','data'=>$cliente],202);
            }
        }
        catch (\Exception $e) {
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

            $pedido = Pedido::findOrFail($request->pedidoId);
            $pedido->cantidad = $request->cantidad;
            $pedido->total = $request->total;
            if ($pedido->save()<=0) {
                $errors++;
            }

            $detalle = DetallePedido::findOrFail($request->id);
            $detalle->producto_id = $request->producto_id;
            if ($detalle->save()<=0) {
                $errors++;
            }

            //consultando si hubieron errores
            if ($errors == 0) {
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$pedido],202);
            }
            else {
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>$pedido],209);
            }
        }
        catch (\Exception $e) {
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
    public function cambiarEstado(Request $request) {
        try {
            $pedido = Pedido::findOrFail($request->id);
            $pedido->estado = $request->estado;
            if ($pedido->save()>=1) {
                return response()->json(['status'=>'OK','data'=>$pedido],202);
            }
        }
        catch (\Exception $e) {
            $e->getMessage();
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
    public function filterState(Request $request) {
        $estado = $request->state;
        try {
            $pedido = DetallePedido::join('clientes','detalle_pedidos.cliente_id','=','clientes.id')
            ->join('pedidos','detalle_pedidos.pedido_id','=','pedidos.id')
            ->join('productos','detalle_pedidos.producto_id','=','productos.id')
            ->select('detalle_pedidos.id','clientes.id as clienteId','detalle_pedidos.producto_id','pedidos.id as pedidoId','clientes.nombre as cliente','clientes.telefono','productos.nombre as producto','pedidos.cantidad','pedidos.total','pedidos.fecha','pedidos.hora','pedidos.estado')
            ->where('pedidos.estado','=',$estado)
            ->orderBy('id','asc')->get();
            return $pedido;
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function filterCliente(Request $request) {
        $cliente = $request->client;
        try {
            $pedido = DetallePedido::join('clientes','detalle_pedidos.cliente_id','=','clientes.id')
            ->join('pedidos','detalle_pedidos.pedido_id','=','pedidos.id')
            ->join('productos','detalle_pedidos.producto_id','=','productos.id')
            ->select('detalle_pedidos.id','clientes.id as clienteId','pedidos.id as pedidoId','clientes.nombre as cliente','clientes.telefono','productos.nombre as producto','pedidos.cantidad','pedidos.total','pedidos.fecha','pedidos.hora','pedidos.estado')
            ->where('clientes.nombre','=',$cliente)
            ->orderBy('id','asc')->get();
            return $pedido;
        } 
        catch (\Exception $e) {
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
