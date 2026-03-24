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
            Schema::create('personas', function (Blueprint $table) {
                $table->bigIncrements('id_persona')->primary();
                $table->string('nombres');
                $table->string('ap_paterno');
                $table->string('ap_materno');
                $table->string('documento', 20);
                $table->string('email')->nullable();
                $table->string('telefono', 50)->nullable();
                $table->text('direccion')->nullable();
                $table->bigInteger('id_iglesia')->nullable();
                $table->timestamps();

                // $table->foreign('id_iglesia')->references('id_iglesia')->on('iglesia_iglesias');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('personas');
        }
    };
