<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = "tipo_productos";
    use HasFactory;
    
    //Relación 1 a muchos con productos
    public function productos() {
        return $this->hasMany(Producto::class,'id');
    }
}
