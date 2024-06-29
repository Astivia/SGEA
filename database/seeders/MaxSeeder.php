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
            'nombre'=>'Maximiliano',
            'ap_pat'=>'Astivia',
            'ap_mat'=>'Castellanos',
            'curp'=>'AICM000101HMCSSXA7',
            'email'=>'mastiviac@toluca.tecnm.mx',
            'password'=> Hash::make('123')
        ]);
        $user->assignRole(1);
    }
}
