<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CorporacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('corporacions')->insertOrIgnore([
            'id_corporacion' => 1,
            'nombre' => 'División Sudamericana',
            'siglas' => 'DSA',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('Corporación: División Sudamericana (DSA) creada.');
    }
}
