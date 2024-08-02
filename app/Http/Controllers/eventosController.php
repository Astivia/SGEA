<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\eventos;
use App\Models\participantes;
use App\Models\articulos;
use App\Models\articulosAutores;
use App\Models\revisoresArticulos;

class EventosController extends Controller
{

    public function __construct(){
        $this->middleware('can:eventos.edit')->only('edit','update');
        $this->middleware('can:eventos.create')->only('create','store'); 
        $this->middleware('can:eventos.destroy')->only('destroy'); 
    }

    public function index()
    {
       
        $Eventos = eventos::OrderBy('edicion')->get();
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


    public function show(string $id)
    {
        $evento=eventos::find($id);
        return view ('Eventos.read',compact('evento'));
    }

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

    public function destroy(string $id)
    {
        $evento=eventos::find($id);

        if ((articulos::where('evento_id', $id)->count() > 0) ||
            $evento->participantes->count() > 0||
            articulosAutores::where('evento_id',$id)->count() > 0 ||
            revisoresArticulos::where('evento_id',$id)->count() >0 ) {
            return redirect()->back()->with('error', 'No se puede eliminar: hay Informacion asociada con este evento');
        }
        $logo = $evento->logo;

        $evento->delete();

        $otherEventsUsingLogo = eventos::where('logo', $logo)->count();

        if ($otherEventsUsingLogo == 0 && $logo != 'NO ASIGNADO') {
            $filePath = public_path('assets/uploads/' . $logo);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return redirect('eventos')->with('info', 'evento eliminado');
    }

    public function migrarDatos(Request $request) {
        try {
            DB::statement('SELECT migrar_datos()');
            $request->session()->put('eventoID',null);
            return response()->json(['message' => 'Migracion de datos Satisfactoria'], 200);
        } catch (\Exception $e) {
            Log::error('Error en migracion de datos: ' . $e->getMessage());
            return response()->json(['message' => 'Fallo la migracion de datos', 'error' => $e->getMessage()], 500);
        }
    }

    public function migrarEvento(Request $request, $evento_id) {
        try {
            DB::statement('SELECT migrar_datosPorEvento(?)', [$evento_id]);
            $request->session()->put('eventoID',null);
            return response()->json(['message' => 'Migracion de datos Satisfactoria'], 200);
        } catch (\Exception $e) {
            Log::error('Error en migracion de datos: ' . $e->getMessage());
            return response()->json(['message' => 'Fallo la migracion de datos', 'error' => $e->getMessage()], 500);
        }

    }
    //eliminacion masiva 
    // public function deleteMultiple(Request $request)
    // {
    //     $ids = $request->ids;
    //     if (!empty($ids)) {
    //         eventos::whereIn('id', $ids)->delete();
    //         return response()->json(['success' => "Registros eliminados correctamente."]);
    //     }
    //     return response()->json(['error' => "No se seleccionaron registros."]);
    // }
    public function deleteMultiple(Request $request){
    $ids = $request->ids;

    if (!empty($ids)) {
        foreach ($ids as $id) {
            $evento = eventos::find($id);

            if (!$evento) {
                return response()->json(['error' => "No se encontró el evento con id: $id"], 404);
            }

            if ((articulos::where('evento_id', $id)->count() > 0) ||
                $evento->participantes->count() > 0 ||
                articulosAutores::where('evento_id', $id)->count() > 0 ||
                revisoresArticulos::where('evento_id', $id)->count() > 0) {
                return response()->json(['error' => "No se puede eliminar el evento con id: $id: hay información asociada con este evento"], 400);
            }

            $logo = $evento->logo;

            $evento->delete();

            $otherEventsUsingLogo = eventos::where('logo', $logo)->count();

            if ($otherEventsUsingLogo == 0 && $logo != 'NO ASIGNADO') {
                $filePath = public_path('assets/uploads/' . $logo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        return response()->json(['success' => "Eventos eliminados correctamente."]);
    }

    return response()->json(['error' => "No se seleccionaron eventos."], 400);
}

}