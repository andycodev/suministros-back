<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = [
            [
                'id_periodo'   => 2026,
                'nombre'       => 'Plan Maná 2026',
                'fecha_inicio' => '2026-01-01',
                'fecha_fin'    => '2026-12-31',
                'activo'       => true,
                'es_actual'    => true, // Marcamos este como el periodo vigente
            ],
            [
                'id_periodo'   => 2025,
                'nombre'       => 'Plan Maná 2025',
                'fecha_inicio' => '2025-01-01',
                'fecha_fin'    => '2025-12-31',
                'activo'       => true,
                'es_actual'    => false,
            ],
            [
                'id_periodo'   => 2024,
                'nombre'       => 'Plan Maná 2024',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin'    => '2024-12-31',
                'activo'       => true,
                'es_actual'    => false,
            ],
        ];

        foreach ($periodos as $periodo) {
            DB::table('periodos')->updateOrInsert(
                ['id_periodo' => $periodo['id_periodo']],
                array_merge($periodo, [
                    'created_at' => now(),
                ])
            );
        }

        $this->command->info('Periodos del Plan Maná cargados correctamente.');
    }
}
