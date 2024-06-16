<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class LoginController extends Controller
{
    public function register(Request $request){
        //validar datos


        // $participante = Participante::create([
        //     'nombre' => $validatedData['nombre'],
        //     'apellidos' => $validatedData['apellidos'],
        //     'email' => $validatedData['email'],
        //     'password' => Hash::make($validatedData['password']),
        //     'curp' => $validatedData['curp'],
        // ]);

        // Auth::login($participante);

        $user = new User();

        $user->name= $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        Auth::login($user);
        
        return redirect(route('home'));
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
