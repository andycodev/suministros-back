<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos a la persona que creamos en PersonaSeeder (Director Admin)
        $persona = DB::table('personas')->where('documento', '77777777')->first();

        if ($persona) {
            // 2. Insertamos o actualizamos el usuario vinculado
            DB::table('users')->updateOrInsert(
                ['email' => 'director@admin.com'], // Buscamos por email para no duplicar
                [
                    'name'              => "{$persona->nombres} {$persona->ap_paterno}",
                    'email'             => 'director@admin.com',
                    'email_verified_at' => now(),
                    'password'          => Hash::make('director123'), // IMPORTANTE: Siempre encriptar
                    'id_persona'        => $persona->id_persona,
                    'is_director'       => true,
                    'is_superuser'      => true,
                    'activo'            => true,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]
            );

            $this->command->info('Usuario "director@admin.com" creado y vinculado a la persona.');
        } else {
            $this->command->error('No se pudo crear el usuario: No existe una persona con DNI 77777777.');
        }
    }
}
