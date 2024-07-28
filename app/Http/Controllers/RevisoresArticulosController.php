<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\revisoresArticulos;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\articulos;
use App\Models\areas;
use Illuminate\Support\Str;

class RevisoresArticulosController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index($eventoId)
    {
        $articles = articulos::whereIn('id', function ($query) {
                $query->select('articulo_id')->from('revisores_articulos');
            })->with('revisores.usuario')->get();
        

        //catalogos 
        $articulos = articulos::select('id', 'titulo')->OrderBy('titulo')->get();
        $areas= areas::select('id', 'nombre')->OrderBy('nombre')->get();
        $usuarios=usuarios::where('estado',"alta,registrado")->get();
        return view ('Revisores_Articulos.index',compact('articulos','areas','usuarios','articles'));
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

        if ($request->has('revisores')) {
            $Revisores = json_decode($request->input('revisores'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Recorrer el array de autores seleccionados

                foreach ($Revisores  as $index => $revisor) {
                    if (!is_null($revisor['id'])){
                        $usu=usuarios::where('id',$revisor['id'])->first();
                        revisoresArticulos::create([
                            'evento_id'=>$request->session()->get('eventoID'),
                            'articulo_id'=> $request->input('articulo_id'),
                            'usuario_id'=> $usu->id,
                            'orden'=>  $index + 1 
                        ]);
                    }
                }
            } else {
                echo "Error al decodificar Datos: ".json_last_error_msg();
            }
            return redirect()->back()->with('success', 'Se ha Registrado correctamente');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $articulo=articulos::where('id',$id)->first();
        return view ('Revisores_Articulos.read',compact('articulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($eventoId,$id)
    {
        $articulo=articulos::where('id',$id)->first();
        $revisores = revisoresArticulos::where('articulo_id',$id)->OrderBy('orden')->get();

        //catalogos 
        $usuarios=usuarios::where('estado',"alta,registrado")->get();

        return view('Revisores_Articulos.edit',compact('revisores','articulo','usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $evento_id=$request->session()->get('eventoID');

        $datos=$request->all();

        if ($request->has('revisores')) {
            $Revisors = json_decode($request->input('revisores'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Eliminar autores actuales
                revisoresArticulos::where('articulo_id', $id)->where('evento_id',$evento_id)->delete();
                foreach ($Revisors as $index =>$revisor) {
                    if (!is_null($revisor['id'])){
                            revisoresArticulos::create([
                            'evento_id' => $evento_id,
                            'articulo_id' => $id,
                            'usuario_id' => $revisor['id'],
                            'orden'=>  $index + 1 
                        ]);
                    }
                }
            } else {
                echo "Error al decodificar Datos: ".json_last_error_msg();
            }
        }

        return redirect ($evento_id.'/revisoresArticulos')->with('info','Informacion Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($eventoId,$usuarioId,$articuloId)
    {
        $RevArt=revisores_articulos::where('evento_id',$eventoId)->where('usuario_id',$usuarioId)->where('articulo_id',$articuloId);
        if ($RevArt) {
            $RevArt->delete();
            return redirect()->back()->with('info', 'El revisor se eliminó del artículo');
        } else {
            return redirect()->back()->with('error', 'No se encontró el revisor para eliminar');
        }
    }
}
