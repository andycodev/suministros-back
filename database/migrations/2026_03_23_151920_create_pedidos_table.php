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
            $table->bigIncrements('id_pedido');
            $table->bigInteger('id_persona');
            $table->bigInteger('id_destino');
            $table->bigInteger('id_periodo');
            $table->bigInteger('id_iglesia')->nullable();
            $table->string('codigo', 50)->unique('s_pedidos_codigo_key');
            $table->char('tipo', 1)->default('P');
            $table->char('tipo_suscripcion', 1)->default('F')->comment('Tipo de suscripción: F (Físico), V (Virtual)');
            $table->integer('total_cantidad')->nullable()->default(0);
            $table->decimal('total_monto', 10)->nullable()->default(0);
            $table->string('estado', 20)->nullable()->default('CREADO');
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
            $table->decimal('saldo_pendiente', 10)->nullable()->default(0);

            $table->foreign(['id_destino'], 'pedidos_id_destino_fkey')->references(['id_persona'])->on('personas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_periodo'], 'pedidos_id_periodo_fkey')->references(['id_periodo'])->on('periodos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_persona'], 'pedidos_id_persona_fkey')->references(['id_persona'])->on('personas')->onUpdate('no action')->onDelete('no action');
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
