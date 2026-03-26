<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonaSeeder extends Seeder
{
    public function run(): void
    {
        // Intentamos obtener una iglesia real de las que insertaste por SQL
        // Si no hay ninguna aún, devolverá null
        // $iglesiaId = DB::table('iglesia_iglesias')->value('id_iglesia');
        $iglesiaId = DB::table('iglesia_iglesias')->where('id_iglesia', 1155)->value('id_iglesia');

        DB::table('personas')->updateOrInsert(
            ['documento' => '77777777'], // Evita duplicados por DNI
            [
                'nombres'    => 'Director',
                'ap_paterno' => 'Admin',
                'ap_materno' => 'System',
                'email'      => 'director@admin.com',
                'telefono'   => '987654321',
                'direccion'  => 'Av. Programación 404',
                'id_iglesia' => $iglesiaId, // Si es null, la DB lo acepta por tu definición
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Persona "Director Admin" creada correctamente.');
    }
}
