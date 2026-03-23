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
        Schema::create('iglesia_distritos', function (Blueprint $table) {
            $table->bigInteger('id_distrito')->primary();
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->bigInteger('id_campo');
            $table->bigInteger('id_region')->nullable();
            $table->bigInteger('id_union');
            $table->bigInteger('id_organizacion')->nullable();
            $table->timestamps();
            $table->integer('distrito_7cloud')->nullable();

            $table->foreign(['id_campo'])->references(['id_campo'])->on('iglesia_campos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_organizacion'])->references(['id_organizacion'])->on('organizacions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_region'])->references(['id_region'])->on('iglesia_regions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_union'])->references(['id_union'])->on('iglesia_unions')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iglesia_distritos');
    }
};
