<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\articulosAutores;

class ArticulosAutoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($eventoId)
    {
        $autores= articulosAutores::distinct('usuario_id')->where('evento_id',$eventoId)->OrderBy('usuario_id')->get();
        return view('Autores.index',compact('autores'));
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
    public function edit($eventoId, $id)
    {
        $autor=articulosAutores::where('usuario_id',$id)->where('evento_id',$eventoId)->first();
        return view('Autores.edit',compact('autor'));
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
        return redirect($request->session()->get('eventoID').'/autores')->with('info','Informacion Actualizada');
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
