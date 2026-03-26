<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regiones = [
            // ANoP (17693)
            ['id_region' => 1, 'nombre' => 'Región Valle I', 'id_campo' => 17693, 'cloud' => 10952],
            ['id_region' => 2, 'nombre' => 'Región Valle II', 'id_campo' => 17693, 'cloud' => 22171],
            ['id_region' => 3, 'nombre' => 'Región Cajamarca I', 'id_campo' => 17693, 'cloud' => 21831],
            ['id_region' => 4, 'nombre' => 'Región Trujillo III', 'id_campo' => 17693, 'cloud' => 15231],
            ['id_region' => 5, 'nombre' => 'Región Huamachuco', 'id_campo' => 17693, 'cloud' => 18927],
            ['id_region' => 6, 'nombre' => 'Región Trujillo II', 'id_campo' => 17693, 'cloud' => 824],
            ['id_region' => 7, 'nombre' => 'Región Trujillo IV', 'id_campo' => 17693, 'cloud' => 18117],
            ['id_region' => 8, 'nombre' => 'Región Bambamarca', 'id_campo' => 17693, 'cloud' => 16153],
            ['id_region' => 9, 'nombre' => 'Región Cajamarca II', 'id_campo' => 17693, 'cloud' => 9321],
            ['id_region' => 10, 'nombre' => 'Región Celendín', 'id_campo' => 17693, 'cloud' => 4811],
            ['id_region' => 11, 'nombre' => 'Región Trujillo I', 'id_campo' => 17693, 'cloud' => 22158],
            ['id_region' => 12, 'nombre' => 'Región Chilete', 'id_campo' => 17693, 'cloud' => 14585],
            ['id_region' => 13, 'nombre' => 'Instituciones ANoP', 'id_campo' => 17693, 'cloud' => 15445],

            // APCE (17293)
            ['id_region' => 14, 'nombre' => 'Region 3', 'id_campo' => 17293, 'cloud' => 24422],
            ['id_region' => 15, 'nombre' => 'Región 2', 'id_campo' => 17293, 'cloud' => 23698],
            ['id_region' => 16, 'nombre' => 'Región 5', 'id_campo' => 17293, 'cloud' => 32988],
            ['id_region' => 17, 'nombre' => 'Región 4', 'id_campo' => 17293, 'cloud' => 24318],
            ['id_region' => 18, 'nombre' => 'Región 6', 'id_campo' => 17293, 'cloud' => 8689],
            ['id_region' => 19, 'nombre' => 'Instituciones APCE', 'id_campo' => 17293, 'cloud' => 12149],
            ['id_region' => 20, 'nombre' => 'Región 1', 'id_campo' => 17293, 'cloud' => 3568],

            // MiCOP (17393)
            ['id_region' => 21, 'nombre' => 'Zona IV', 'id_campo' => 17393, 'cloud' => 20382],
            ['id_region' => 22, 'nombre' => 'Zona II', 'id_campo' => 17393, 'cloud' => 25063],
            ['id_region' => 23, 'nombre' => 'Zona I', 'id_campo' => 17393, 'cloud' => 3398],
            ['id_region' => 24, 'nombre' => 'Zona VI', 'id_campo' => 17393, 'cloud' => 36738],
            ['id_region' => 25, 'nombre' => 'Zona III', 'id_campo' => 17393, 'cloud' => 12019],
            ['id_region' => 26, 'nombre' => 'Zona VII', 'id_campo' => 17393, 'cloud' => 6729],
            ['id_region' => 27, 'nombre' => 'Zona V', 'id_campo' => 17393, 'cloud' => 15535],
            ['id_region' => 28, 'nombre' => 'Instituciones MiCOP', 'id_campo' => 17393, 'cloud' => 37884],

            // MiNOP (17993)
            ['id_region' => 29, 'nombre' => 'Rioja', 'id_campo' => 17993, 'cloud' => 2799],
            ['id_region' => 30, 'nombre' => 'Amazonas', 'id_campo' => 17993, 'cloud' => 23539],
            ['id_region' => 31, 'nombre' => 'Yurimaguas', 'id_campo' => 17993, 'cloud' => 12498],
            ['id_region' => 32, 'nombre' => 'Huallaga', 'id_campo' => 17993, 'cloud' => 11335],
            ['id_region' => 33, 'nombre' => 'Moyobamba', 'id_campo' => 17993, 'cloud' => 4849],
            ['id_region' => 34, 'nombre' => 'Nueva Cajamarca', 'id_campo' => 17993, 'cloud' => 20453],
            ['id_region' => 35, 'nombre' => 'Tarapoto Norte', 'id_campo' => 17993, 'cloud' => 41838],
            ['id_region' => 36, 'nombre' => 'Tarapoto Sur', 'id_campo' => 17993, 'cloud' => 39102],
            ['id_region' => 37, 'nombre' => 'Instituciones MNO', 'id_campo' => 17993, 'cloud' => 12387],

            // MPN (17893)
            ['id_region' => 38, 'nombre' => 'Chiclayo Sur', 'id_campo' => 17893, 'cloud' => 5145],
            ['id_region' => 39, 'nombre' => 'Alto Piura', 'id_campo' => 17893, 'cloud' => 24188],
            ['id_region' => 40, 'nombre' => 'Chiclayo Norte', 'id_campo' => 17893, 'cloud' => 17250],
            ['id_region' => 41, 'nombre' => 'Bajo Piura', 'id_campo' => 17893, 'cloud' => 6107],
            ['id_region' => 42, 'nombre' => 'Chiclayo Centro', 'id_campo' => 17893, 'cloud' => 507],
            ['id_region' => 43, 'nombre' => 'Tumbes', 'id_campo' => 17893, 'cloud' => 14878],
            ['id_region' => 44, 'nombre' => 'Bagua', 'id_campo' => 17893, 'cloud' => 14408],
            ['id_region' => 45, 'nombre' => 'Cajamarca', 'id_campo' => 17893, 'cloud' => 22288],
            ['id_region' => 46, 'nombre' => 'Jaén', 'id_campo' => 17893, 'cloud' => 24106],
            ['id_region' => 47, 'nombre' => 'Instituciones MPN', 'id_campo' => 17893, 'cloud' => 6137],
        ];

        foreach ($regiones as $reg) {
            DB::table('iglesia_regions')->updateOrInsert(
                ['id_region' => $reg['id_region']],
                [
                    'nombre'          => $reg['nombre'],
                    'activo'          => true,
                    'id_campo'        => $reg['id_campo'],
                    'id_union'        => 17112, // Todas estas pertenecen a la Unión Norte
                    'id_organizacion' => 1,
                    'region_7cloud'   => $reg['cloud'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }

        $this->command->info('Regiones cargadas correctamente.');
    }
}
