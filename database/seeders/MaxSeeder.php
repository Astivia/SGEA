<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\usuarios;

class MaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = usuarios::create([
            'foto' => 'DefaultH.jpg',
            'nombre'=>'Maximiliano',
            'ap_paterno'=>'Astivia',
            'ap_materno'=>'Castellanos',
            'curp'=>'AICM000101HMCSSXA7',
            'email'=>'mastiviac@toluca.tecnm.mx',
            'password'=> Hash::make('123'),
            'telefono'=>'7226292587',
            'estado'=>'alta,registrado'
        ]);
        $user2 = usuarios::create([
            'foto' => 'DefaultH.jpg',
            'nombre'=>'Luis Eduardo',
            'ap_paterno'=>'Gallegos',
            'ap_materno'=>'Garcia',
            'curp'=>'LGGE000502HMCSSXB6',
            'email'=>'lgallegosg@toluca.tecnm.mx',
            'password'=> Hash::make('123'),
            'telefono'=>'7292451298',
            'estado'=>'alta,registrado'
        ]);
        $user->assignRole(1);
        $user2->assignRole(1);
    }
}
