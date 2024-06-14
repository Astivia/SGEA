<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\participantes;
use App\Models\eventos;

class ParticipantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Participantes = participantes::OrderBy('nombre')->get();
        $Eventos=eventos::all();

        return view ('Participantes.index',compact('Participantes','Eventos'));
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

        $this->validate($request, [
            'evento_id' => 'required|integer',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'curp' => 'required|string',
            'email' => 'required|email',
        ]);
        $datos=$request->all();
        participantes::create($datos);
        return redirect ('/participantes');
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
        $events=eventos::all();

        $part=participantes::find($id);
        return view ('Participantes.edit',compact('part','events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $NuevosDatos = $request->all();
        $participante=participantes::find($id);
        $participante->update($NuevosDatos);
        return redirect('/participantes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $participante=participantes::find($id);
        $participante->delete();

        return redirect('participantes');
    }
}
