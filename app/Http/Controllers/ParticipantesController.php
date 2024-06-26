<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use App\Models\participantes;
use App\Models\eventos;
use App\Models\autores;
use App\Models\comite_editorial;
use App\Models\revisores_articulos;
use App\Models\participantes_areas;
use App\Models\revisores_areas;

class ParticipantesController extends Controller
{

    public function __construct(){
        $this->middleware('can:participantes.index')->only('index');
        $this->middleware('can:participantes.edit')->only('edit','update');
        $this->middleware('can:participantes.create')->only('create','store'); 
        $this->middleware('can:participantes.destroy')->only('destroy'); 

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Participantes = participantes::OrderBy('nombre')->get();
        $Eventos=eventos::all();
        

        return view ('Participantes.index',compact('Participantes','Eventos'));
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
        
        //validacion de datos
        $this->validate($request, [
            'nombre' => 'required|string',
            'ap_pat' => 'required|string',
            'ap_mat' => 'required|string',
            'curp' => 'required|string',
            'email' => 'required|email',
        ]);
        $datos=$request->all();
        
        //verificamos que no exista la curp
        if (participantes::where('curp', $datos['curp'])->exists()) {
            
            return redirect()->back()->with('error', 'Ya existe un participante con la CURP ingresada.No se guardaron los datos');
        }
        $datos['password'] = Hash::make($datos['password']);
        participantes::create($datos);
        return redirect('/participantes')->with('success', 'Se ha Registrado correctamente');
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
        $events=eventos::all();
        $part=participantes::find($id);
        $roles =Role::All();
        return view ('Participantes.edit',compact('part','events','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $NuevosDatos = $request->all();
        $participante=participantes::find($id);
        //asignamos Rol elejido
        $participante->roles()->sync($request->roles);

        //encriptamos la nueva contraseña
        $NuevosDatos['password'] = Hash::make($NuevosDatos['password']);
        //actualizamos
        $participante->update($NuevosDatos);
         return redirect('/participantes')->with('info','Se guardaron los cambios de manera satisfactoria');
        // return redirect()->route('participantes.edit',$participante)->with('info','Se guardaron los cambios de manera satisfactoria');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $participante=participantes::find($id);
        if ((autores::where('participante_id', $participante->id)->count() > 0)||
            (comite_editorial::where('participante_id', $participante->id)->count() > 0)||
            (revisores_articulos::where('participante_id', $participante->id)->count() > 0)||
            (participantes_areas::where('participante_id', $participante->id)->count() > 0)||
            (revisores_areas::where('participante_id', $participante->id)->count() > 0)) {
              
            return redirect()->back()->with('error', 'No se puede eliminar el participante porque aun tiene algun cargo asociado');
        }
        $participante->delete();

        return redirect('participantes')->with('success', 'Participante eliminado correctamente');
    }
}
