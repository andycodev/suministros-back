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
        Schema::create('iglesia_unions', function (Blueprint $table) {
            $table->bigInteger('id_union')->primary();
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->bigInteger('id_organizacion')->nullable();
            $table->timestamps();

            $table->foreign(['id_organizacion'])->references(['id_organizacion'])->on('organizacions')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iglesia_unions');
    }
};
