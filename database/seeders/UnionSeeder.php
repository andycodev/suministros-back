<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnionSeeder extends Seeder
{
    public function run(): void
    {
        $uniones = [
            [
                'id_union'        => 17112,
                'nombre'          => 'Unión Peruana del Norte',
                'siglas'          => 'UPN',
                'activo'          => true,
                'orden'           => 1,
                'id_organizacion' => 1, // La que creamos en OrganizacionSeeder
            ],
            [
                'id_union'        => 7111,
                'nombre'          => 'Unión Peruana del Sur',
                'siglas'          => 'UPS',
                'activo'          => true,
                'orden'           => 2,
                'id_organizacion' => 1,
            ],
        ];

        foreach ($uniones as $union) {
            DB::table('iglesia_unions')->updateOrInsert(
                ['id_union' => $union['id_union']], // Clave de búsqueda
                array_merge($union, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Uniones (UPN y UPS) cargadas correctamente.');
    }
}
