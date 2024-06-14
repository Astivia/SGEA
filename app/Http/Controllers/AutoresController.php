<?php

namespace App\Http\Controllers;
use App\Models\autores;
use App\Models\participantes;
use App\Models\eventos;

use Illuminate\Http\Request;

class AutoresController extends Controller
{

    public function combo_autoresPorEvento($evento_id){
        $participantes= participantes::select('id','nombre','apellidos')
                        ->where('evento_id',$evento_id)->get();
        return $participantes;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Autores=autores::OrderBy('id')->get();

        $Eventos= eventos::all();
        $Participantes = participantes::OrderBy('nombre')->get();

        return view ('Autores.index', compact('Autores','Participantes','Eventos'));
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
