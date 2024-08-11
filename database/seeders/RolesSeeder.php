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

        Permission::create(['name'=>'eventos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.destroy'])->syncRoles([$rol1,$rol2]);

        Permission::create(['name'=>'participantes.create'])->syncRoles([$rol1,$rol2,$rol5]);
        Permission::create(['name'=>'participantes.read'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'participantes.destroy'])->syncRoles([$rol1,$rol2,$rol5]);
        


    }
}
