<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\revisoresArticulos;
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
        $RevArt= revisoresArticulos::where('evento_id',$eventoId)->OrderBy('articulo_id')->get();

        $parts = $evento->participantes->mapWithKeys(function($participante) {
            $nombreCompleto = $participante->nombre . ' ' . $participante->ap_paterno . ' ' .$participante->ap_materno;
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
        
        //actualizamos los datos del articulo
        $articulo = articulos::find($datos['articulo_id']);
        $articulo->estado = "Pendiente de revision";
        $articulo->save(); 

        //verificamos que el dato no este registrado
        $verificacion = revisoresArticulos::whereRaw('evento_id = ? AND usuario_id = ? AND articulo_id = ?', [$datos['evento_id'], $datos['usuario_id'], $datos['articulo_id']]);


        if($verificacion->count() > 0){
            return redirect()->back()->with('error', 'Este usuario ya esta asignado al articulo');

        }else{
            revisoresArticulos::create([
                'evento_id'=> $datos['evento_id'],
                'usuario_id'=> $datos['usuario_id'],
                'articulo_id'=>$datos['articulo_id'],
                'puntuacion'=>null,
                'comentarios'=>null
            ]);
        }
        return redirect()->back()->with('success', 'Se ha Registrado correctamente');
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
    public function edit(revisoresArticulos $ra)
    {
        
        $eventoId = $ra['evento_id'];
        

        $evento = eventos::find($eventoId);

        $parts = $evento->participantes->mapWithKeys(function($participante) {
            $nombreCompleto = $participante->nombre . ' ' . $participante->ap_pat . ' ' .$participante->ap_mat;
            return [$participante->id => $nombreCompleto];
        });

        $articulos = articulos::where('evento_id', $eventoId)->get();
        $articulosOptions = $articulos->map(function ($articulo) {
            return [$articulo->id => Str::limit($articulo->titulo, 50)];
        })->toArray();

        // Set the currently assigned participant and article IDs
        $selectedParticipante = $ra->usuario_id;
        $selectedArticulo = $ra->articulo_id;

        return view('Revisores_Articulos.edit', compact('ra', 'evento', 'parts', 'articulosOptions', 'selectedParticipante', 'selectedArticulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, revisoresArticulos $RevArt)
    {
        $NuevosDatos = $request->all();
        dd($NuevosDatos);
        $ra=revisoresArticulos::find($id);
        $ra->update($NuevosDatos);
        return redirect('/participantes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($eventoId,$usuarioId,$articuloId)
    {
        $RevArt=revisores_articulos::where('evento_id',$eventoId)->where('usuario_id',$usuarioId)->where('articulo_id',$articuloId);
        if ($RevArt) {
            $RevArt->delete();
            return redirect()->back()->with('success', 'El revisor se eliminó del artículo');
        } else {
            return redirect()->back()->with('error', 'No se encontró el revisor para eliminar');
        }
    }
}
