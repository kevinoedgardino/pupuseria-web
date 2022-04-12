<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    //Relacion inversa con tipo productos
    public function tipoProducto() {
        return $this->belongsTo(TipoProducto::class,'tipoprod_id');
    }

    //Relacion 1 a muchos con detalle productos
    public function detallePedidos() {
        return $this->hasMany(DetallePedido::class,'id');
    }
}
