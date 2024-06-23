<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        roles::create([
            'nombre'=> 'Administrador',
            'descripcion'=>'Tiene acceso a todo el esquema BD, con permisos de Lectura,Escritura,Eliminacion e Insercion de datos en todas las tablas'
        ]);
        roles::create([
            'nombre'=> 'Organizador',
            'descripcion'=>'Tiene acceso a tablas esenciales de la BD, pero no a todas, unica y exclusivamente a las que funcionen para organizar el evento'
        ]);
        roles::create([
            'nombre'=> 'Publico',
            'descripcion'=>'Tiene acceso solo a algunas tablas de la BD. Su acceso es controlado y no tiene privilegios elevados sobre la mayoria de tablas'
        ]);
    }
}
