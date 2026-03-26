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
        Schema::create('iglesia_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_region')->primary();
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('id_campo');
            $table->unsignedBigInteger('id_union');
            $table->unsignedBigInteger('id_organizacion')->nullable();
            $table->timestamps();
            $table->integer('region_7cloud')->nullable();

            $table->foreign('id_campo')->references('id_campo')->on('iglesia_campos');
            $table->foreign('id_organizacion')->references('id_organizacion')->on('organizacions');
            $table->foreign('id_union')->references('id_union')->on('iglesia_unions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iglesia_regions');
    }
};
