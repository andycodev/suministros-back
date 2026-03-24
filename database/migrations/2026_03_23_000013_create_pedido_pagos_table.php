<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedido_pagos', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->bigInteger('id_pedido');
            $table->string('transaccion_id')->unique('pedido_pagos_transaccion_id_key');
            $table->decimal('monto', 10)->default(0);
            $table->string('metodo_pago', 50)->nullable()->default('EFECTIVO');
            $table->string('estado_visanet', 100)->nullable()->default('COMPLETADO');
            $table->text('comprobante_path')->nullable();
            $table->jsonb('raw_response')->nullable();
            $table->timestamp('fecha_pago')->nullable()->default(DB::raw("now()"));

            $table->foreign(['id_pedido'], 'pedido_pagos_id_pedido_fkey')->references(['id_pedido'])->on('pedidos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_pagos');
    }
};
