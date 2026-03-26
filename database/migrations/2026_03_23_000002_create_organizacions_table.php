<?php

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
        Schema::create('organizacions', function (Blueprint $table) {
            $table->bigIncrements('id_organizacion');
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->string('lugar', 2000);
            $table->unsignedBigInteger('id_entidad')->nullable();
            $table->string('id_depto')->nullable();
            $table->unsignedBigInteger('id_corporacion')->nullable();
            $table->timestamps();

            $table->foreign('id_corporacion')->references('id_corporacion')->on('corporacions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizacions');
    }
};
