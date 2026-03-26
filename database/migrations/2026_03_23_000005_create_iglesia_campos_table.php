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
        Schema::create('iglesia_campos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_campo')->primary();
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('id_union');
            $table->unsignedBigInteger('id_organizacion')->nullable();
            $table->timestamps();

            $table->foreign('id_organizacion')->references('id_organizacion')->on('organizacions');
            $table->foreign('id_union')->references('id_union')->on('iglesia_unions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iglesia_campos');
    }
};
