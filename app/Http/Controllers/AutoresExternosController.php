<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\autores_externos;
use App\Models\articulos;

class AutoresExternosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $autoresExt=autores_externos::All();

        return view ('AutoresExt.index',compact('autoresExt'));
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

        if($datos['sexo']==1){
            $datos['foto'] = 'DefaultH.jpg';
        }else {
            $datos['foto'] = 'DefaultM.jpg';
        }


        autores_externos::create($datos);
        return redirect()->back()->with('success', 'Se ha Registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ae = autores_externos::find($id);

        return view ('AutoresExt.read',compact('ae'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $ae=autores_externos::find($id);
        return view ('AutoresExt.edit',compact('ae'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $autor=autores_externos::find($id);

        if(is_null($NuevosDatos['afiliacion'])){
            return redirect()->back()->with('error', 'el campo Afiliacion no puede estar vacio');
        }

        $autor->update($NuevosDatos);
        return redirect('/autores_externos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $autor=autores_externos::find($id);

        $articulosConAutor = articulos::whereHas('autoresExternos', 
            function ($query)use ($autor) {
                $query->where('autor_id_ext', $autor->id);
            })->count();

        if ($articulosConAutor  > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el autor, tiene articulos asociados');
        }


        $autor->delete();

        return redirect('autores_externos')->with('success', 'Se ha eliminado correctamente');
    }
}
