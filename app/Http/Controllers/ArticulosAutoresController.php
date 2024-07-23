<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\articulosAutores;

class ArticulosAutoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $autores= articulosAutores::distinct('usuario_id')->OrderBy('usuario_id')->get();
        return view('Articulos_Autores.index',compact('autores'));
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
        //
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
        $autor=articulosAutores::where('usuario_id',$id)->first();
        return view('Articulos_Autores.edit',compact('autor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $datos=$request->all();
        articulosAutores::where('usuario_id', $id)->update([
            'email' => $datos['corresp-email'],
            'institucion' => $datos['institucion'],
        ]);
        return redirect('/autores')->with('info','Informacion Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario=usuarios::find($id);

        if ($usuario->eventos()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar porque está registrado en uno o más eventos');
        }else if (articulosAutores::where('usuario_id', $usuario->id)->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el usuario porque esta registrado como Autor');
        }
        $usuario->delete();
        return redirect('usuarios')->with('info', 'Usuario eliminado correctamente');
    }
}
