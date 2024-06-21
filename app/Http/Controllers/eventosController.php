<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eventos;
use App\Models\participantes;
use App\Models\articulos;
use App\Models\comite_editorial;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Eventos = eventos::all();

        return view ('Eventos.index',compact('Eventos'));
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
        // Obtener el archivo imagen
        $file = $datos['img'];

        if($file){

            // Definir la ruta donde se guardará el archivo
            $destinationPath = public_path('assets/uploads');
            // Crear la carpeta si no existe
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            // Generar un nombre único para el archivo
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            // Mover el archivo a la ruta especificada
            $file->move($destinationPath, $fileName);
            //guardamos solo el nombre en la BD
            $datos['img'] = $fileName;
        }else{
            $datos['img'] = 'NO ASIGNADO';
        }
        eventos::create($datos);
        return redirect ('/eventos')->with('success', 'Se ha Registrado el evento');
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
        $evento=eventos::find($id);
        return view ('Eventos.edit',compact('evento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $evento=eventos::find($id);
        // Obtener el archivo imagen
        $file = $NuevosDatos['img'];
        
        // Definir la ruta donde se guardará el archivo
        $destinationPath = public_path('assets/uploads');
        // Crear la carpeta si no existe
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        // Generar un nombre único para el archivo
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        // Mover el archivo a la ruta especificada
        $file->move($destinationPath, $fileName);
        //guardamos solo el nombre en la BD
        $NuevosDatos['img'] = $fileName;

        $evento->update($NuevosDatos);
        return redirect('/eventos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento=eventos::find($id);

        if ((articulos::where('evento_id', $evento->id)->count() > 0) ||
            (participantes::where('evento_id', $evento->id)->count() > 0)||
            (comite_editorial::where('evento_id', $evento->id)->count() > 0) ) {
            return redirect()->back()->with('error', 'No se puede eliminar: hay Informacion asociada con este evento');
        }

        $evento->delete();

        return redirect('eventos')->with('success', 'evento eliminado de forma Satisfactoria');
    }
}
