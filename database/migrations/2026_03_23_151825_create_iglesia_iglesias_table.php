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
        Schema::create('iglesia_iglesias', function (Blueprint $table) {
            $table->bigInteger('id_iglesia')->primary();
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->string('codigo_iglesia', 500)->nullable();
            $table->string('tipo_congregacion', 2)->default('01');
            $table->bigInteger('id_entidad')->nullable();
            $table->string('id_depto')->nullable();
            $table->bigInteger('id_region')->nullable();
            $table->bigInteger('id_distrito');
            $table->bigInteger('id_campo');
            $table->bigInteger('id_union');
            $table->timestamps();
            $table->integer('entidadid_7cloud')->nullable();
            $table->string('clave_natural', 50)->nullable();
            $table->bigInteger('id_persona_created')->nullable();

            $table->foreign(['id_campo'])->references(['id_campo'])->on('iglesia_campos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_distrito'])->references(['id_distrito'])->on('iglesia_distritos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_persona_created'])->references(['id_persona'])->on('personas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_region'])->references(['id_region'])->on('iglesia_regions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_union'])->references(['id_union'])->on('iglesia_unions')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iglesia_iglesias');
    }
};
