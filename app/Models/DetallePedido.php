<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = "detalle_pedidos";
    use HasFactory;

    //Relacion inversa con productos
    public function producto() {
        return $this->belongsTo(Producto::class,'producto_id');
    }
    
    //Relacion inversa con clientes
    public function cliente() {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    //Relacion inversa con pedidos
    public function pedido() {
        return $this->belongsTo(Pedido::class,'pedido_id');
    }
}
