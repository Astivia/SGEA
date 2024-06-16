<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eventos;
use App\Models\participantes;
use App\Models\articulos;
use App\Models\comite_editorial;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Eventos = eventos::all();

        return view ('Eventos.index',compact('Eventos'));
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
        eventos::create($data);
        return redirect ('/eventos')->with('success', 'Se ha Registrado el evento');
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
        $evento=eventos::find($id);
        return view ('Eventos.edit',compact('evento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $evento=eventos::find($id);
        $evento->update($NuevosDatos);
        return redirect('/eventos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento=eventos::find($id);

        if ((articulos::where('evento_id', $evento->id)->count() > 0) ||
            (participantes::where('evento_id', $evento->id)->count() > 0)||
            (comite_editorial::where('evento_id', $evento->id)->count() > 0) ) {
            return redirect()->back()->with('error', 'No se puede eliminar: hay Informacion asociada con este evento');
        }

        $evento->delete();

        return redirect('eventos')->with('success', 'evento eliminado de forma Satisfactoria');
    }
}
