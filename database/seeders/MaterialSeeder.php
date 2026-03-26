<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materiales = [
            // --- TIPO 'P' (Publicaciones y Revistas) ---
            ['nombre' => 'Guía de Escuela Sabática Adultos - Trimestral', 'precio' => 12.50, 'tipo' => 'P'],
            ['nombre' => 'Guía de Escuela Sabática Jóvenes', 'precio' => 11.00, 'tipo' => 'P'],
            ['nombre' => 'Guía de Escuela Sabática Infantes', 'precio' => 15.00, 'tipo' => 'P'],
            ['nombre' => 'Guía de Escuela Sabática Primarios', 'precio' => 15.00, 'tipo' => 'P'],
            ['nombre' => 'Libro Misionero 2026: El Gran Conflicto', 'precio' => 2.50, 'tipo' => 'P'],
            ['nombre' => 'Matutina Adultos: Un día a la vez', 'precio' => 45.00, 'tipo' => 'P'],
            ['nombre' => 'Matutina Jóvenes: Pasos Firmes', 'precio' => 38.00, 'tipo' => 'P'],
            ['nombre' => 'Matutina Damas: Perfume de Gracia', 'precio' => 40.00, 'tipo' => 'P'],
            ['nombre' => 'Matutina Menores: Aventuras con Dios', 'precio' => 35.00, 'tipo' => 'P'],
            ['nombre' => 'Revista Adventista (Suscripción Anual)', 'precio' => 60.00, 'tipo' => 'P'],
            ['nombre' => 'Manual de Iglesia (Edición 2022)', 'precio' => 25.00, 'tipo' => 'P'],
            ['nombre' => 'Himnario Adventista con Música', 'precio' => 55.00, 'tipo' => 'P'],
            ['nombre' => 'Himnario Adventista Letra Grande', 'precio' => 30.00, 'tipo' => 'P'],
            ['nombre' => 'Biblia Misionera con Estudios', 'precio' => 18.00, 'tipo' => 'P'],
            ['nombre' => 'Folleto de Enriquecimiento Espiritual', 'precio' => 5.00, 'tipo' => 'P'],
            ['nombre' => 'Curso Bíblico "La Fe de Jesús"', 'precio' => 3.50, 'tipo' => 'P'],
            ['nombre' => 'Curso Bíblico "Yo Creo"', 'precio' => 4.00, 'tipo' => 'P'],
            ['nombre' => 'Agenda Plan Maná 2026', 'precio' => 12.00, 'tipo' => 'P'],
            ['nombre' => 'Libro: El Camino a Cristo', 'precio' => 5.00, 'tipo' => 'P'],
            ['nombre' => 'Libro: El Deseado de todas las Gentes', 'precio' => 25.00, 'tipo' => 'P'],
            ['nombre' => 'Folleto Grupo Pequeño - Líder', 'precio' => 3.00, 'tipo' => 'P'],
            ['nombre' => 'Folleto Grupo Pequeño - Participante', 'precio' => 2.00, 'tipo' => 'P'],
            ['nombre' => 'Libro: Historia de los Conquistadores', 'precio' => 15.00, 'tipo' => 'P'],
            ['nombre' => 'Cuadernillo de Clase: Amigo', 'precio' => 4.50, 'tipo' => 'P'],
            ['nombre' => 'Cuadernillo de Clase: Guía Mayor', 'precio' => 7.00, 'tipo' => 'P'],

            // --- TIPO 'I' (Insignias y Uniformes) ---
            ['nombre' => 'Pañoleta de Conquistadores', 'precio' => 15.00, 'tipo' => 'I'],
            ['nombre' => 'Tubo de Pañoleta Plástico', 'precio' => 3.00, 'tipo' => 'I'],
            ['nombre' => 'Tubo de Pañoleta Metal (Lujo)', 'precio' => 12.00, 'tipo' => 'I'],
            ['nombre' => 'Cordón de Mando Rojo (Director)', 'precio' => 18.00, 'tipo' => 'I'],
            ['nombre' => 'Botón de Especialidad: Computación', 'precio' => 2.50, 'tipo' => 'I'],
            ['nombre' => 'Botón de Especialidad: Primeros Auxilios', 'precio' => 2.50, 'tipo' => 'I'],
            ['nombre' => 'Botón de Especialidad: Arte de Acampar', 'precio' => 2.50, 'tipo' => 'I'],
            ['nombre' => 'Galón de Clase: Guía Mayor Master', 'precio' => 8.50, 'tipo' => 'I'],
            ['nombre' => 'Galón de Clase: Explorador', 'precio' => 5.00, 'tipo' => 'I'],
            ['nombre' => 'Escudo del Club de Conquistadores (Manga)', 'precio' => 4.00, 'tipo' => 'I'],
            ['nombre' => 'Escudo del Club de Aventureros (Manga)', 'precio' => 4.00, 'tipo' => 'I'],
            ['nombre' => 'Tira de Nombre de Campo (UPN)', 'precio' => 3.50, 'tipo' => 'I'],
            ['nombre' => 'Pañoleta de Aventureros', 'precio' => 13.00, 'tipo' => 'I'],
            ['nombre' => 'Insignia de Clase: Abejitas Laboriosas', 'precio' => 3.00, 'tipo' => 'I'],
            ['nombre' => 'Insignia de Clase: Rayitos de Sol', 'precio' => 3.00, 'tipo' => 'I'],
            ['nombre' => 'Bandera de Conquistadores (Oficial)', 'precio' => 85.00, 'tipo' => 'I'],
            ['nombre' => 'Bandera de Aventureros (Oficial)', 'precio' => 75.00, 'tipo' => 'I'],
            ['nombre' => 'Silbato de Mando Profesional', 'precio' => 25.00, 'tipo' => 'I'],
            ['nombre' => 'Gorra oficial J.A.', 'precio' => 20.00, 'tipo' => 'I'],
            ['nombre' => 'Pin Logotipo J.A. Metal', 'precio' => 6.00, 'tipo' => 'I'],
            ['nombre' => 'Banda de Especialidades (Grande)', 'precio' => 22.00, 'tipo' => 'I'],
            ['nombre' => 'Banda de Especialidades (Mediana)', 'precio' => 18.00, 'tipo' => 'I'],
            ['nombre' => 'Insignia Mundial de Conquistadores', 'precio' => 5.00, 'tipo' => 'I'],
            ['nombre' => 'Botón de Investidura: Liderazgo', 'precio' => 4.50, 'tipo' => 'I'],
            ['nombre' => 'Parche Evento: Camporee 2026', 'precio' => 5.00, 'tipo' => 'I'],
        ];

        foreach ($materiales as $mat) {
            DB::table('materiales')->insert([
                'nombre'      => $mat['nombre'],
                'descripcion' => "Suministro oficial de tipo " . ($mat['tipo'] == 'P' ? 'Publicación' : 'Insignia'),
                'precio'      => $mat['precio'],
                'tipo'        => $mat['tipo'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $this->command->info('Materiales cargados correctamente.');
    }
}
