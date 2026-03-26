<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('organizacions')->updateOrInsert(
            ['siglas' => 'UPN'], // Si ya existe la UPN, la actualiza en lugar de duplicarla
            [
                'nombre'         => 'Unión Peruana del Norte',
                'activo'         => true,
                'lugar'          => 'XXX',
                'id_entidad'     => 17112,
                'id_depto'       => '0',
                'id_corporacion' => 1, // Vinculado a la Corporación del Seeder anterior
                'created_at'     => now(),
                'updated_at'     => now()
            ]
        );

        $this->command->info('Organización: Unión Peruana del Norte (UPN) creada.');
    }
}
