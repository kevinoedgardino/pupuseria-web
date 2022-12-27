<?php

namespace Database\Seeders;

use App\Models\TipoProducto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $tipoProducto = new TipoProducto();
        $tipoProducto->nombre = "Pupusa";
        $tipoProducto->save();

        $tipoProducto = new TipoProducto();
        $tipoProducto->nombre = "Bebida";
        $tipoProducto->save();
    }
}
