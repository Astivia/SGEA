<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\revisores_articulos;
use App\Models\eventos;
use App\Models\articulos;
use Illuminate\Support\Str;

class RevisoresArticulosController extends Controller
{

    public function __construct(){
        $this->middleware('can:revisores_articulos.index')->only('index');
        $this->middleware('can:revisores_articulos.edit')->only('edit','update');
        $this->middleware('can:revisores_articulos.create')->only('create','store'); 
        $this->middleware('can:revisores_articulos.destroy')->only('destroy'); 

    }
    /**
     * Display a listing of the resource.
     */
    public function index($eventoId)
    {
        $evento = eventos::find($eventoId); 
        $RevArt= revisores_articulos::OrderBy('articulo_id')->get();

        $parts = $evento->participantes->mapWithKeys(function($participante) {
            $nombreCompleto = $participante->nombre . ' ' . $participante->ap_pat . ' ' .$participante->ap_mat;
            return [$participante->id => $nombreCompleto];
        });
        $articulos = articulos::where('evento_id', $eventoId)->get();

        $articulosOptions = $articulos->map(function ($articulo) {
            return [$articulo->id => Str::limit($articulo->titulo, 50)];
        })->toArray();

        // dd($articulosOptions);
        return view ('Revisores_Articulos.index',compact('RevArt','evento','parts','articulosOptions'));
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
        $ra=revisores_articulos::find($id);

        $Participantes=participantes::all();
        $Articulos = articulos::select('titulo')->distinct()->get();

        return view('Revisores_Articulos.edit', compact('Articulos','Participantes','ra'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        dd($NuevosDatos);
        $ra=revisores_articulos::find($id);
        $ra->update($NuevosDatos);
        return redirect('/participantes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
