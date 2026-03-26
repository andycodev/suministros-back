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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id_pedido')->unsigned();
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_destino');
            $table->unsignedBigInteger('id_periodo');
            $table->unsignedBigInteger('id_iglesia')->nullable();
            $table->string('codigo', 50)->unique('s_pedidos_codigo_key');
            $table->char('tipo', 1)->default('P')->comment('Tipo de pedido: P (Personal), I (Iglesia)');
            $table->char('tipo_suscripcion', 1)->default('F')->comment('Tipo de suscripción: F (Físico), V (Virtual)');
            $table->integer('total_cantidad')->nullable()->default(0);
            $table->decimal('total_monto', 10, 2)->nullable()->default(0);
            $table->string('estado', 20)->nullable()->default('CREADO');
            $table->decimal('saldo_pendiente', 10, 2)->nullable()->default(0);
            $table->timestamps();

            $table->foreign('id_persona')->references('id_persona')->on('personas');
            $table->foreign('id_destino')->references('id_persona')->on('personas');
            $table->foreign('id_periodo')->references('id_periodo')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
