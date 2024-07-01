<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol1 =Role::create(['name'=>'Administrador']);
        $rol2 =Role::create(['name'=>'Organizador']);
        
        Permission::create(['name'=>'eventos.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.destroy'])->syncRoles([$rol1]);

        Permission::create(['name'=>'areas.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'areas.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'areas.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'areas.destroy'])->syncRoles([$rol1]);

        Permission::create(['name'=>'comite_editorial.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'comite_editorial.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'comite_editorial.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'comite_editorial.destroy'])->syncRoles([$rol1]);

        Permission::create(['name'=>'participantes.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.destroy'])->syncRoles([$rol1]);

        Permission::create(['name'=>'autores.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'autores.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'autores.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'autores.destroy'])->syncRoles([$rol1]);
        
        Permission::create(['name'=>'articulos.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'articulos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'articulos.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'articulos.destroy'])->syncRoles([$rol1]);
        
        Permission::create(['name'=>'revisores_articulos.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'revisores_articulos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'revisores_articulos.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'revisores_articulos.destroy'])->syncRoles([$rol1]);
        
        Permission::create(['name'=>'usuarios.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'usuarios.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'usuarios.edit'])->syncRoles([$rol1]);
        Permission::create(['name'=>'usuarios.destroy'])->syncRoles([$rol1]);
    }
}
