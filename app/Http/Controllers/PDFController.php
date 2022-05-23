<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
//use Barryvdh\DomPDF\PDF;
use PDF;

class PDFController extends Controller
{
    public function pdfPedidos() {

        $pedidos = DetallePedido::join('clientes','detalle_pedidos.cliente_id','=','clientes.id')
            ->join('pedidos','detalle_pedidos.pedido_id','=','pedidos.id')
            ->join('productos','detalle_pedidos.producto_id','=','productos.id')
            ->select(
                'detalle_pedidos.id',
                'clientes.id as clienteId',
                'detalle_pedidos.producto_id',
                'pedidos.id as pedidoId',
                'clientes.nombre as cliente',
                'clientes.telefono',
                'productos.nombre as producto',
                'pedidos.cantidad',
                'pedidos.total',
                'pedidos.fecha',
                'pedidos.hora',
                'pedidos.estado'
            )
            ->orderBy('id','asc')->get();
            $pdf = \PDF::loadView('admin.listPedidosPDF',compact('pedidos'));
            return $pdf->stream('pedidos.pdf');
    }
}
