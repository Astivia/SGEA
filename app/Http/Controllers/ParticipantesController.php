<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

use App\Models\eventos;
use App\Models\usuarios;
use App\Models\participantes;


class ParticipantesController extends Controller
{

    public function __construct(){
        $this->middleware('can:participantes.index')->only('index');
        $this->middleware('can:participantes.edit')->only('edit','update');
        $this->middleware('can:participantes.create')->only('create','store'); 
        $this->middleware('can:participantes.destroy')->only('destroy'); 

    }

    public function index($eventoId)
    {
        $evento = eventos::find($eventoId); 
        $part = $evento->participantes; 
        
        $usuarios=usuarios::OrderBy('ap_paterno')->get();
        return view ('Participantes.index',compact('part','evento','usuarios'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->all();

        // Verificar que el evento y el usuario existan
        $evento = eventos::find($datos['evento_id']);
        $usuario = usuarios::find($datos['usuario_id']);
    
        if (!$evento || !$usuario) {
            return redirect()->back()->with('error', 'Evento o usuario no encontrado.');
        }

        if(!participantes::where('evento_id',$datos['evento_id'])->where('usuario_id',$datos['usuario_id'])->first()){
            $evento->participantes()->attach($usuario);
        }else{
            return redirect()->back()->with('error', 'Este usuario ya es parte del evento');
        }
    
        // Verificar si el usuario autenticado es el mismo que se está registrando
        if (Auth::user()->id === $usuario->id) {
            $request->session()->put('eventoID', $evento->id);
        }
    
        return redirect()->back()->with('success', 'Se ha añadido correctamente.')->with('reload', true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($eventoId,$usuarioId)
    {
        $evento = eventos::find($eventoId);
        $usuario = usuarios::find($usuarioId);

        if (!$evento || !$usuario) {
            return redirect()->back()->with('error', 'Evento o Usuario no encontrado.');
        }

        $evento->participantes()->detach($usuario);
        if (Auth::user()->id === $usuario->id) {
            session()->put('eventoID', null);
        }    

        return redirect()->back()->with('info', 'Usuario expulsado del evento.')->with('reload', true);
    }
}
