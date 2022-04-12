<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    //Relacion de 1 a muchos con detalle pedidos
    public function detallePedidos() {
        return $this->hasMany(DetallePedido::class,'id');
    }
}
