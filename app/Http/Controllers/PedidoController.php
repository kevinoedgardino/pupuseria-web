<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\Cliente;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
     * 
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
