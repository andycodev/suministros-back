<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. NIVELES JERÁRQUICOS (Indispensables para las llaves foráneas)
        $this->call([
            CorporacionSeeder::class,
            OrganizacionSeeder::class,
            UnionSeeder::class,
            CampoSeeder::class,   // <--- ESTE FALTA (Crea el ID 17893)
            RegionSeeder::class,  // <--- ESTE TAMBIÉN (Necesario para los distritos)
        ]);

        // 2. CARGA DE DISTRITOS (SQL)
        if (DB::table('iglesia_distritos')->count() === 0) {
            $this->command->info('Importando Distritos desde SQL...');
            $pathDistritos = database_path('sql/distritos.sql');
            if (File::exists($pathDistritos)) {
                // Usamos un try-catch o simplemente aseguramos que los padres existen
                DB::unprepared(File::get($pathDistritos));
            }
        }

        // 3. CARGA DE IGLESIAS (SQL)
        if (DB::table('iglesia_iglesias')->count() === 0) {
            $this->command->info('Importando Iglesias desde SQL...');
            $pathIglesias = database_path('sql/iglesias.sql');
            if (File::exists($pathIglesias)) {
                DB::unprepared(File::get($pathIglesias));
            }
        }

        // 4. DATOS DE IDENTIDAD Y ACCESO
        $this->call([
            PersonaSeeder::class,
            UserSeeder::class,
            PeriodoSeeder::class,
            MaterialSeeder::class,
        ]);
    }
}
