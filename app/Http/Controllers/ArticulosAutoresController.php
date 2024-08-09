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

    public function show($eventoId, $id)
    {
        $autor=articulosAutores::where('usuario_id',$id)->where('evento_id',$eventoId)->first();
        if($autor->usuario->foto === "DefaultH.jpg" ||$autor->usuario->foto === "DefaultM.jpg" ){
            $url='SGEA/storage/app/public/users/profile';
        }else{
            $url='SGEA/storage/app/public/users/profile/'.$autor->usuario->curp;
        }
        $autor->usuario->foto = $url.'/'.$autor->usuario->foto;
        $articulos=articulosAutores::where('usuario_id',$id)->get();

        return view('Autores.read',compact('autor','articulos'));
    }


    public function edit($eventoId, $id)
    {
        $autor=articulosAutores::where('usuario_id',$id)->where('evento_id',$eventoId)->first();
        return view('Autores.edit',compact('autor'));
    }

    public function update(Request $request, string $id)
    {
        $datos=$request->all();
        articulosAutores::where('usuario_id', $id)->update([
            'email' => $datos['corresp-email'],
            'institucion' => $datos['institucion'],
        ]);
        return redirect($request->session()->get('eventoID').'/autores')->with('info','Informacion Actualizada');
    }


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
