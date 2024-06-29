<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Models\comite_editorial;
USE App\Models\eventos;
USE App\Models\participantes;

class Comite_EditorialController extends Controller
{

    public function __construct(){
        $this->middleware('can:comite_editorial.index')->only('index');
        $this->middleware('can:comite_editorial.edit')->only('edit','update');
        $this->middleware('can:comite_editorial.create')->only('create','store'); 
        $this->middleware('can:comite_editorial.destroy')->only('destroy'); 

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Comite=comite_editorial::all();

        $Eventos=eventos::all();
        $Participantes=participantes::OrderBy('nombre')->get();


        return view ('Comite_Editorial.index',compact('Comite','Eventos','Participantes'));
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
        $data=$request->all();
        comite_editorial::create($data);
        return redirect ('/comite_editorial')->with('success', 'Se registro el participante en el comite editorial');
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
        $part=comite_editorial::find($id);

        $Eventos=eventos::all();
        $Participantes=participantes::OrderBy('nombre')->get();

        return view ('Comite_Editorial.edit',compact('part','Eventos','Participantes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        //buscamos el articulo
        $Partcomite=comite_editorial::find($id);
        //insertamos en articulo
        $Partcomite->update($NuevosDatos);

        return redirect('/comite_editorial');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Comite=comite_editorial::find($id);
        $Comite->delete();

        return redirect('comite_editorial')->with('success', 'Participante eliminado del Comite');
    }
}
