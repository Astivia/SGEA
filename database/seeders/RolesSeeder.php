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
        $rol2 =Role::create(['name'=>'Comite']);
        $rol3 =Role::create(['name'=>'Autor']);
        $rol4 =Role::create(['name'=>'Revisor']);
        $rol5 =Role::create(['name'=>'Invitado']);

        Permission::create(['name'=>'areas.index'])->syncRoles([$rol1]);
        Permission::create(['name'=>'areas.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'areas.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'areas.read'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'areas.destroy'])->syncRoles([$rol1,$rol2]);

        Permission::create(['name'=>'eventos.index'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'eventos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.read'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'eventos.destroy'])->syncRoles([$rol1,$rol2]);
        
        Permission::create(['name'=>'usuarios.index'])->syncRoles([$rol1]);
        Permission::create(['name'=>'usuarios.create'])->syncRoles([$rol1,$rol2,$rol3,$rol5]);
        Permission::create(['name'=>'usuarios.edit'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'usuarios.read'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'usuarios.destroy'])->syncRoles([$rol1]);

        Permission::create(['name'=>'participantes.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.read'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name' =>'participantes.create'])->syncRoles([$rol1,$rol2,$rol3,$rol5]);
        Permission::create(['name' =>'participantes.destroy'])->syncRoles([$rol1,$rol5]);

        
        Permission::create(['name'=>'articulos.index'])->syncRoles([$rol1,$rol2,$rol3]);
        Permission::create(['name'=>'articulos.create'])->syncRoles([$rol1,$rol2,$rol3,$rol5]);
        Permission::create(['name'=>'articulos.edit'])->syncRoles([$rol1,$rol2,$rol3,$rol5]);
        Permission::create(['name'=>'articulos.read'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'articulos.destroy'])->syncRoles([$rol1,$rol2,$rol3,$rol5]);

        Permission::create(['name'=>'articulos_autores.index'])->syncRoles([$rol1]);
        Permission::create(['name'=>'articulos_autores.create'])->syncRoles([$rol1,$rol2,$rol3,$rol5]);
        Permission::create(['name'=>'articulos_autores.edit'])->syncRoles([$rol1,$rol2,$rol3]);
        Permission::create(['name'=>'articulos_autores.read'])->syncRoles([$rol1,$rol2,$rol3,$rol4,$rol5]);
        Permission::create(['name'=>'articulos_autores.destroy'])->syncRoles([$rol1,$rol2,$rol3]);
        
        Permission::create(['name'=>'revisores_articulos.index'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'revisores_articulos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'revisores_articulos.edit'])->syncRoles([$rol1,$rol2,$rol4]);
        Permission::create(['name'=>'revisores_articulos.read'])->syncRoles([$rol1,$rol2,$rol3,$rol4]);
        Permission::create(['name'=>'revisores_articulos.destroy'])->syncRoles([$rol1,$rol2]);

    }
}
