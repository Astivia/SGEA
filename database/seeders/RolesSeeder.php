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
        
        Permission::create(['name'=>'eventos.create'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.edit'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name'=>'eventos.destroy'])->syncRoles([$rol1,$rol2]);
        
        

        


    }
}
