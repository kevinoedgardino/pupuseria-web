<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')
            ->nullable()
            ->constrained('productos')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('cliente_id')
            ->nullable()
            ->constrained('clientes')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('pedido_id')
            ->nullable()
            ->constrained('pedidos')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};
