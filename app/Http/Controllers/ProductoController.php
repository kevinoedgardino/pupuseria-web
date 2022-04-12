<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Prophecy\Promise\ReturnPromise;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class ProductoController extends Controller
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
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->estado = "D";
            $producto->precio = $request->precio;
            $producto->tipoprod_id = $request->tipoprod_id;
            if ($producto->save()>=1) {
                return response()->json(['status'=>'OK','data'=>$producto],201);
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
            $producto = Producto::join('tipo_productos','productos.tipoprod_id','=','tipo_productos.id')
            ->select('productos.id','productos.nombre','productos.estado','productos.precio','tipo_productos.nombre as tipo')
            ->orderBy('id','desc')->get();
            return $producto;
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Metodo para filtrar los productos por su estado
     * 
     * D = Disponible
     * N = No disponible
     * 
     */
    public function filterStateType(Request $request) {
        $estado = $request->state;
        $tipo = $request->tipo;
        try {
            $producto = Producto::join('tipo_productos','productos.tipoprod_id','=','tipo_productos.id')
            ->select('productos.id','productos.nombre','productos.estado','productos.precio','productos.cantidad','productos.tipoprod_id','tipo_productos.nombre as tipo')
            ->where('productos.estado','=',$estado)
            ->where('tipo_productos.nombre','=',$tipo)
            ->orderBy('id','asc')->get();
            return $producto;
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function filterState(Request $request) {
        $estado = $request->state;
        try {
            $producto = Producto::join('tipo_productos','productos.tipoprod_id','=','tipo_productos.id')
            ->select('productos.id','productos.nombre','productos.estado','productos.precio','productos.cantidad','productos.tipoprod_id','tipo_productos.nombre as tipo')
            ->where('productos.estado','=',$estado)
            ->orderBy('id','asc')->get();
            return $producto;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $producto = Producto::findOrFail($request->id);
            $producto->nombre = $request->nombre;
            $producto->precio = $request->precio;
            $producto->tipoprod_id = $request->tipoprod_id;
            if ($producto->save()>=1) {
                return response()->json(['status'=>'OK','data'=>$producto],202);
            }
        } 
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Metodo para cambiar el estado del producto
     * 
     * D = Disponible
     * N = No disponible
     * 
     */
    public function cambiarEstado(Request $request) {
        try {
            $producto = Producto::findOrFail($request->id);
            $producto->estado = $request->estado;
            if ($producto->save()>=1) {
                return response()->json(['status'=>'OK','data'=>$producto],202);
            }
        }
        catch (\Exception $e) {
            $e->getMessage();
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
      //De momento esto no se va a eliminar
    }
}
