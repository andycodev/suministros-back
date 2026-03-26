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
            $table->unsignedBigInteger('id_iglesia')->primary();
            $table->string('nombre', 500);
            $table->string('siglas', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->string('codigo_iglesia', 500)->nullable();
            $table->string('tipo_congregacion', 2)->default('01');
            $table->unsignedBigInteger('id_entidad')->nullable();
            $table->string('id_depto')->nullable();
            $table->unsignedBigInteger('id_region')->nullable();
            $table->unsignedBigInteger('id_distrito');
            $table->unsignedBigInteger('id_campo');
            $table->unsignedBigInteger('id_union');
            $table->timestamps();
            $table->integer('entidadid_7cloud')->nullable();
            $table->string('clave_natural', 50)->nullable();
            $table->unsignedBigInteger('id_persona_created')->nullable();

            $table->foreign('id_campo')->references('id_campo')->on('iglesia_campos');
            $table->foreign('id_distrito')->references('id_distrito')->on('iglesia_distritos');
            $table->foreign('id_persona_created')->references('id_persona')->on('personas');
            $table->foreign('id_region')->references('id_region')->on('iglesia_regions');
            $table->foreign('id_union')->references('id_union')->on('iglesia_unions');
        });

        // 2. TRUCO: Aquí mismo activamos la relación en la tabla personas
        Schema::table('personas', function (Blueprint $table) {
            $table->foreign('id_iglesia')
                ->references('id_iglesia')
                ->on('iglesia_iglesias')
                ->onDelete('set null'); // O 'no action' según prefieras
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Al revertir, primero quitamos la foránea de personas para evitar errores
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['id_iglesia']);
        });

        Schema::dropIfExists('iglesia_iglesias');
    }
};
