<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\areas;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        areas::create([
            'nombre'=>'Computación',
            'descripcion'=>'Temas relacionados con la informática'
        ]);
        areas::create([
            'nombre'=>'Residuos',
            'descripcion'=>'Articulos que se enfocan en la gestión, tratamiento y valorización de residuos'
        ]);
        areas::create([
            'nombre'=>'Energia',
            'descripcion'=>'Abarca temas relacionados con la producción, distribución y uso de la energía'
        ]);
        areas::create([
            'nombre'=>'Nanotecnología',
            'descripcion'=>'Estudio y la manipulación de la materia a escala nanométrica'
        ]);
        areas::create([
            'nombre'=>'Sociedad',
            'descripcion'=>'Abarca una amplia gama de temas relacionados con las ciencias sociales y humanidades'
        ]);
    }
}
