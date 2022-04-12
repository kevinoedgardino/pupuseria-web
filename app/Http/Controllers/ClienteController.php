<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use GuzzleHttp\Client;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "Vista de clientes";
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
            $cliente = new Cliente();
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            if ($cliente->save()>=1) {
                return response()->json(['status'=>'ok','data'=>$cliente],201);
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
            $cliente = Cliente::orderBy('id','asc')->get();
            return $cliente;    
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $cliente = Cliente::findOrFail($request->id);
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            if ($cliente->save()>=1) {
                return response()->json(['status'=>'ok','data'=>$cliente],202);
            }    
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
    public function destroy(Request $request)
    {
        try {
            $cliente = Cliente::findOrFail($request->id);
            $cliente->delete();
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
