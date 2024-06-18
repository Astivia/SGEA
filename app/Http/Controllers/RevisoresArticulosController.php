<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\revisores_articulos;
use App\Models\participantes;
use App\Models\articulos;

class RevisoresArticulosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $RevArt= revisores_articulos::OrderBy('articulo_id')->get();

        $Participantes=participantes::all();
        $Articulos = articulos::select('titulo')->distinct()->get();

        

        return view ('Revisores_Articulos.index',compact('RevArt','Participantes','Articulos'));
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
        // dd($datos);

        $articulo=articulos::where('titulo',$datos['articulo_titulo'])->first();

        revisores_articulos::create([
            'participante_id'=> $datos['participante_id'],
            'articulo_id'=>$articulo->id
        ]);

        return redirect ('revisores_articulos');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
