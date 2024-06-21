<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Models\User;
use App\Models\participantes;


class LoginController extends Controller
{
    public function register(Request $request){
        //validacion de datos

        //recolectamos los datos en una variable

        $Datos=$request->all();

        //validamos que NO exista un participante ya registrado con la misma CURP

        if (participantes::where('curp', $Datos['curp'])->exists()) {
            
            return redirect()->back()->with('error', 'Ya existe un participante con la CURP ingresada');

        }else{

            //Encriptamos la contraseña y Creamos el usuario en la BD
            $Datos['password'] = Hash::make($Datos['password']);
            $user = participantes::create($Datos);

            //Iniciamos sesion con el usuario recien creado y lo redirigimos al dashboard
            Auth::login($user);
            return redirect(route('home'));
        }
        
    }

    public function login(Request $request){
        //validacion de datos

        $credentials =[
            "email"=> $request['email'],
            "password"=> $request['password']
        ];

        //mantener iniciada la sesion
        $remember = ($request->has('remember') ? true :false);

        if(Auth::attempt($credentials,$remember)){

            $request->session()->regenerate();

            return redirect()->intended(route('home'));

        }else{
            return redirect('login');
        }
    }

    public function logout (Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
