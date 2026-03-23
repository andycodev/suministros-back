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
        Schema::create('materiales', function (Blueprint $table) {
            $table->bigIncrements('id_material');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10)->default(0);
            $table->char('tipo', 1)->default('P');
            $table->boolean('activo')->default(true);
            $table->timestamp('created_at')->nullable()->default(DB::raw("now()"));
            $table->timestamp('updated_at')->nullable()->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
