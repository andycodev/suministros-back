<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('s_periodos', function (Blueprint $table) {
            $table->bigInteger('id_periodo')->primary();
            $table->string('nombre', 50);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->boolean('es_actual')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_periodos');
    }
};
