<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use App\Models\eventos;
use App\Models\usuarios;


class ParticipantesController extends Controller
{

    public function __construct(){
        $this->middleware('can:participantes.index')->only('index');
        $this->middleware('can:participantes.edit')->only('edit','update');
        $this->middleware('can:participantes.create')->only('create','store'); 
        $this->middleware('can:participantes.destroy')->only('destroy'); 

    }
    /**
     * Display a listing of the resource.
     */
    public function index($eventoId)
    {
        $evento = eventos::find($eventoId); 
        // Obtener usuarios que asisten
        $part = $evento->participantes; 

    
        $usuarios=usuarios::OrderBy('ap_paterno')->get();

        return view ('Participantes.index',compact('part','evento','usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos=$request->all();
        $evento=eventos::find($datos['evento_id']);
        $usuario= usuarios::find($datos['usuario_id']);
        $evento->participantes()->attach($usuario);
        return redirect()->back()->with('success', 'Se ha Registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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

        return redirect()->back()->with('success', 'Usuario expulsado del evento.');
    }
}
