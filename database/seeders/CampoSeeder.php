<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampoSeeder extends Seeder
{
    public function run(): void
    {
        $campos = [
            // UNIÓN PERUANA DEL SUR (7111)
            ['id_campo' => 7011, 'nombre' => 'MSOP', 'siglas' => 'MSOP', 'id_union' => 7111],
            ['id_campo' => 7311, 'nombre' => 'APC',  'siglas' => 'APC',  'id_union' => 7111],
            ['id_campo' => 7411, 'nombre' => 'MAC',  'siglas' => 'MAC',  'id_union' => 7111],
            ['id_campo' => 7511, 'nombre' => 'MLT',  'siglas' => 'MLT',  'id_union' => 7111],
            ['id_campo' => 7711, 'nombre' => 'MOP',  'siglas' => 'MOP',  'id_union' => 7111],
            ['id_campo' => 7911, 'nombre' => 'APSur', 'siglas' => 'APSur', 'id_union' => 7111],
            ['id_campo' => 72111, 'nombre' => 'MPCS', 'siglas' => 'MPCS', 'id_union' => 7111],

            // UNIÓN PERUANA DEL NORTE (17112)
            ['id_campo' => 17293, 'nombre' => 'APCE',  'siglas' => 'APCE',  'id_union' => 17112],
            ['id_campo' => 17393, 'nombre' => 'MiCOP', 'siglas' => 'MiCOP', 'id_union' => 17112],
            ['id_campo' => 17693, 'nombre' => 'ANoP',  'siglas' => 'ANoP',  'id_union' => 17112],
            ['id_campo' => 17893, 'nombre' => 'MPN',   'siglas' => 'MPN',   'id_union' => 17112],
            ['id_campo' => 17993, 'nombre' => 'MiNOP', 'siglas' => 'MiNOP', 'id_union' => 17112],
        ];

        foreach ($campos as $campo) {
            DB::table('iglesia_campos')->updateOrInsert(
                ['id_campo' => $campo['id_campo']],
                [
                    'nombre'          => $campo['nombre'],
                    'siglas'          => $campo['siglas'],
                    'activo'          => true,
                    'id_union'        => $campo['id_union'],
                    'id_organizacion' => 1,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }

        $this->command->info('Campos/Misiones (Norte y Sur) cargados exitosamente.');
    }
}
