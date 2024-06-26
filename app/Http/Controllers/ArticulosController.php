<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\articulos;
use App\Models\autores;
use App\Models\autores_externos;
use App\Models\eventos;
use App\Models\areas;

class ArticulosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Articulos=articulos::OrderBy('id')->get();

        $Eventos=eventos::all();
        $Areas =areas::all();

        $autores = autores::all();
        $autoresExternos = autores_externos::all();

        $autoresSistema = $autores->mapWithKeys(
            function ($autor) {
                return [$autor->id => $autor->usuario->nombre_completo];
            }
        );

        $autoresExternos = $autoresExternos->mapWithKeys(
            function ($autorExterno) {
                return [$autorExterno->id => $autorExterno->nombre_completo];
            }
        );
        return view ('Articulos.index',compact('Articulos','Eventos','Areas','autoresSistema','autoresExternos'));
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
        //insertamoslos datos del articulo
        $articulo=articulos::create([
            'titulo'=> $datos['titulo'],
            'evento_id'=> $datos['evento_id'],
            'area_id'=> $datos['area_id'],
            'estado'=> 'pendiente de revision', 
        ]);
        //verificamos que campo viene definido para el autor
        if(isset($datos['autor_id_autor'])){
            $articulo->autores()->attach($datos['autor_id_autor']);
        }elseif(isset($datos['autor_id_ext'])){
            $articulo->autoresExternos()->attach($datos['autor_id_ext']);
        }

        return redirect ('/articulos');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $articulo=articulos::find($id);
        
        return view ('Articulos.read',compact('articulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $articulo= articulos::find($id);

        $Eventos = Eventos::all();
        $Areas = Areas::all();

        $autores = autores::all();
        $autoresExternos = autores_externos::all();

        $autoresSistema = $autores->mapWithKeys(
            function ($autor) {
                return [$autor->id => $autor->usuario->nombre_completo];
            }
        );

        $autoresExternos = $autoresExternos->mapWithKeys(
            function ($autorExterno) {
                return [$autorExterno->id => $autorExterno->nombre_completo];
            }
        );
        return view('Articulos.edit', compact('articulo', 'Eventos', 'Areas', 'autoresSistema','autoresExternos'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();

        //buscamos el articulo
        $articulo = articulos::find($id);

        //insertamos en articulo
        $articulo->update([
            'titulo'=>$NuevosDatos['titulo'],
            'area_id'=>$NuevosDatos['area_id'],
            'evento_id'=>$NuevosDatos['evento_id'],
            'estado'=>$NuevosDatos['estado']
        ]);
        //verificamos que campo viene definido para el autor
        if(!is_null($NuevosDatos['autor_id_autor'])){
            $articulo->autores()->detach();
            $articulo->autores()->attach($NuevosDatos['autor_id_autor']);
        }elseif(!is_null($NuevosDatos['autor_id_ext'])){
            $articulo->autoresExternos()->detach();
            $articulo->autoresExternos()->attach($NuevosDatos['autor_id_ext']);
        }
        return redirect('/articulos')->with('success', 'Artículo Modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el artículo a eliminar
        $articulo = articulos::find($id);


        if (!$articulo){
            return redirect()->back()->with('error', 'No se encontro el articulo');
        }elseif ($articulo->autores->count() > 0) {
            $articulo->autores()->detach();
        }else if ($articulo->autoresExternos->count() > 0) {
            $articulo->autoresExternos()->detach();
        }

        $articulo->delete();
        return redirect()->back()->with('success', 'el artículo  se elimino correctamente');
    }
}
