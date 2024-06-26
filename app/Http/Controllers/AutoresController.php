<?php

namespace App\Http\Controllers;
use App\Models\autores;
use App\Models\usuarios;
use App\Models\articulos;

use Illuminate\Http\Request;

class AutoresController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Autores=autores::OrderBy('usuario_id')->get();
        $usuarios=usuarios::All();

        return view ('Autores.index', compact('Autores','usuarios'));
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
        autores::create($datos);
        return redirect ('/autores');
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
        
        $autor=autores::find($id);
        $usuarios=usuarios::all();
        
        return view ('Autores.edit',compact('usuarios','autor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $autor=autores::find($id);
        

        if(is_null($NuevosDatos['afiliacion'])){
            return redirect()->back()->with('error', 'el campo Afiliacion no puede estar vacio');
        }

        $autor->update($NuevosDatos);
        return redirect('/autores');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $autor=autores::find($id);

        $articulosConAutor = articulos::whereHas('autores', 
            function ($query)use ($autor) {
                $query->where('autor_id_autor', $autor->id);
            })->count();

        if ($articulosConAutor  > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el autor, tiene articulos asociados');
        }


        $autor->delete();

        return redirect('autores')->with('success', 'Se ha eliminado correctamente');
    }
}
