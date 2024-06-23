<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\permisos;
use App\Models\roles;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tablas=['eventos','areas','participantes','participantes_areas','articulos','articulos_autores','autores',
            'comite_editorial','revisores_articulos','roles','participantes_roles','permisos','revisores_areas','roles_permisos',
            'talleres','conferencias'
        ];

        for ($i = 0; $i < count($tablas); $i++) {
            permisos::create([
                'nombre' => 'Ver '. $tablas[$i],
                 'descripcion' => 'Permite ver la lista de'.$tablas[$i].' del sistema.'
               ]);
               
           permisos::create([
                 'nombre' => 'Crear '.$tablas[$i],
                 'descripcion' => 'Permite crear nuevos '.$tablas[$i].' en el sistema.'
               ]);
               
           permisos::create([
                 'nombre' => 'Editar '.$tablas[$i],
                 'descripcion' => 'Permite editar la información de los '.$tablas[$i].' existentes.'
               ]);
               
           permisos::create([
                 'nombre' => 'Eliminar '.$tablas[$i],
                 'descripcion' => 'Permite eliminar '.$tablas[$i].' del sistema.'
               ]);
        }

        //asignamos todos los permisos al rol admin
        $adminRole = roles::find(1);
        $permissions = permisos::all();
        foreach ($permissions as $permission) {
            $permission->assignToRole($adminRole->id);
        }
            
    }
}
