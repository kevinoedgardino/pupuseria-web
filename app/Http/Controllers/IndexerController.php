<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexerController extends Controller
{
    public function indexOrdenes() {
        return view('ordenar'); 
    }

    public function indexPedidos() {
        return view('pedidos');
    }

    public function indexCocina() {
        return view('panelcocina');
    }

    public function indexControl() {
        return view('admin.panelcontrol');
    }
}
