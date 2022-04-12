<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    //Relación 1 a muchos con pedidos
    public function pedidos() {
        return $this->hasMany(DetallePedido::class,'id');
    }
}
