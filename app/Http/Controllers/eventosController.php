<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eventos;
use App\Models\participantes;
use App\Models\articulos;
use App\Models\comite_editorial;

class EventosController extends Controller
{

    public function __construct(){
        $this->middleware('can:eventos.edit')->only('edit','update');
        $this->middleware('can:eventos.create')->only('create','store'); 
        $this->middleware('can:eventos.destroy')->only('destroy'); 

    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
       
        $Eventos = eventos::all();
        //consultamos las imagenes en sistema
        $sysImgs = [];
        foreach ($Eventos as $evento) {
            $imageName = $evento->logo; 
            if (!in_array($imageName, $sysImgs)) {
                $sysImgs[] = $imageName;
            }
        }
         return view ('Eventos.index',compact('Eventos','sysImgs'));
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
        $file = $datos['logo'];
        if(!is_string($file)){
            //EL FORMULARIO TIENE UNA IMAGEN
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
                $datos['logo'] = $fileName;
            }else{
                $datos['logo'] = 'NO ASIGNADO';
            }
        }
        $datos['estado'] = 1;
        eventos::create($datos);
        return redirect ('/eventos')->with('success', 'Se ha Registrado el evento');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evento=eventos::find($id);
        date_default_timezone_set('America/Mexico_City');
        $today=date('Y-m-d');
        $message='';

        if( $today==($evento->fecha_inicio) && $today<($evento->fecha_fin) ){
            $message="El evento esta en curso";
        }else if(($evento->fecha_inicio) <$today &&($evento->fecha_fin)<$today){
            $message="El evento ya finalizo";
        }else{
            $message = "El evento esta programado ";
        }
        return view ('Eventos.read',compact('evento','message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $evento=eventos::find($id);

        $Eventos=eventos::all();
        $sysImgs = [];
        foreach ($Eventos as $evento) {
            $imageName = $evento->logo; 
            if (!in_array($imageName, $sysImgs)) {
                $sysImgs[] = $imageName;
            }
        }
        return view ('Eventos.edit',compact('evento','sysImgs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $evento=eventos::find($id);
        // Obtener el archivo imagen
        $file = $NuevosDatos['logo'];

        if(!is_string($file)){
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
            $NuevosDatos['logo'] = $fileName;
        }
        

        $evento->update($NuevosDatos);
        return redirect('/eventos')->with('info','Informacion Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento=eventos::find($id);

        if ((articulos::where('evento_id', $evento->id)->count() > 0) ||
            ($evento->participantes->count() > 0) ) {
            return redirect()->back()->with('error', 'No se puede eliminar: hay Informacion asociada con este evento');
        }

        $evento->delete();

        return redirect('eventos')->with('info', 'evento eliminado de forma Satisfactoria');
    }

}