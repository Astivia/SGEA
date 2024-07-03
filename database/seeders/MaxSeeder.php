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
            'ap_pat'=>'Astivia',
            'ap_mat'=>'Castellanos',
            'curp'=>'AICM000101HMCSSXA7',
            'email'=>'mastiviac@toluca.tecnm.mx',
            'password'=> Hash::make('123')
        ]);
        $user2 = usuarios::create([
            'foto' => 'DefaultH.jpg',
            'nombre'=>'Luis Eduardo',
            'ap_pat'=>'Gallegos',
            'ap_mat'=>'Garcia',
            'curp'=>'LGGE000502HMCSSXB6',
            'email'=>'lgallegosg@toluca.tecnm.mx',
            'password'=> Hash::make('123')
        ]);
        $user->assignRole(1);
        $user2->assignRole(1);
    }
}
