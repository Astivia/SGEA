<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
        $this->middleware('can:eventos.index')->only('index'); 
        $this->middleware('can:eventos.create')->only('create','store'); 
        $this->middleware('can:eventos.edit')->only('edit','update');
        $this->middleware('can:eventos.destroy')->only('destroy'); 
    }

    public function index()
    {
        $Eventos = eventos::OrderBy('edicion')->get();
        //consultamos las imagenes en sistema
        $sysImgs = []; 
        foreach ($Eventos as $evento) {
            $url= 'SGEA/storage/app/public/EventImgs/'.$evento->acronimo.$evento->edicion.'/logo';
            if (!in_array( $evento->logo, $sysImgs)) {
                $sysImgs[] = $url.'/'. $evento->logo;
                $evento->logo = $url.'/'. $evento->logo;
            }
        }
        return view ('Eventos.index',compact('Eventos','sysImgs'));
    }

    public function store(Request $request)
    {
         // Validar los datos de entrada
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'acronimo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'edicion' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
        ]);
        $datos=$validatedData ;
        // Verificar si ya existe un evento con el mismo acrónimo y edición
        if (eventos::where('acronimo', $datos['acronimo'])->where('edicion', $datos['edicion'])->exists()) {
            return response()->json(['success' => false , 'error' => 'El evento ya existe']);
        }
        // Obtener el archivo imagen
        $file = $datos['logo'];
        if(!is_string($file)){
            //EL FORMULARIO TIENE UNA IMAGEN
                // Definir la ruta donde se guardará el archivo
                $destinationPath = storage_path('app/public/EventImgs/'.$datos['acronimo'].$datos['edicion'].'/logo');
                // Crear la carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                // Generar un nombre único para el archivo
                $fileName = time().'.'. $file->getClientOriginalExtension();
                // Mover el archivo a la ruta especificada
                $file->move($destinationPath, $fileName);
                //guardamos solo el nombre en la BD
                $datos['logo'] = $fileName;
           
        }
        $datos['estado'] =1;
        //Crear el evento
        $NewEvent=eventos::create($datos);

        //Crear el archivo de configuracion
        $this->createParameterFile($NewEvent->id);

        return response()->json(['success' => true, 'type' => $datos['acronimo']]);
        
    }

    
    public function show(string $id)
    {
        $evento=eventos::find($id);
        $url= 'SGEA/storage/app/public/EventImgs/'.$evento->acronimo.$evento->edicion.'/logo';
        $evento->logo = $url.'/'. $evento->logo;
        return view ('Eventos.read',compact('evento'));
    }

    public function edit(string $id)
    {
        $evento=eventos::find($id);
        $url = 'SGEA/storage/app/public/EventImgs/'.$evento->acronimo.$evento->edicion.'/logo';
        $actualLogo = $url.'/'.$evento->logo;
     
        //catalogos
        $Eventos=eventos::all();
        $sysImgs = [];
        foreach ($Eventos as $evento) {
            $url2 = 'SGEA/storage/app/public/EventImgs/'.$evento->acronimo.$evento->edicion.'/logo';
            if (!in_array($evento->logo, $sysImgs)) {
                $sysImgs[] = $url2.'/'. $evento->logo;;
            }
        }
        return view ('Eventos.edit',compact('evento','sysImgs','actualLogo'));
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
            return response()->json(['message' => 'Fallo la migracion de datos', 'errores' => $e->getMessage()], 500);
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

    public function cancelEvent($evento_id){
        $evento = eventos::find($evento_id);
        if (!$evento) {
            return redirect()->back()->with('error', 'No se encontró el evento');
        }

        $evento->estado = 4;
        $evento->save(); 
    
        if ($evento->estado !== 4) {
            return redirect()->back()->with('error', 'El evento se ha Cancelado');
        } else {
            return redirect()->back()->with('info', 'El evento ha sido cancelado');
        }
    }

    private function createParameterFile($eventoID){
        $evento = eventos::find($eventoID);
        $data = [
            'Evento' => $evento->acronimo.' '.$evento->edicion,
            'MaxToApprove' => 30,
            'MinToApprove' => 21,
            'Questions' => [
                            "¿El Título es claro, corto y atractivo?",
                            "¿Es de actualidad e interes el tema estudiado?",
                            "¿El trabajo es ameno, con rigor divulgativo y transmite la idea esencial?",
                            "¿Usa un lenguaje sencillo e imagenes descriptivas?",
                            "¿Hace buen uso de la ortografía y la gramática, contiene párrafos cortos y no más de 4 páginas?",
                            "¿El trabajo es accesible a un público no especializado?"
                        ],
            'OptionAnswers' =>['RechazoFuerte','Rechazar','Rechazo debil','Aceptacion debil','Aceptar','Aceptacien fuerte']
        ];
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        // Definir la ruta del archivo dentro del directorio 'storage/app/public'
        $fileName =  $evento->acronimo.$evento->edicion.'parameter.json';
        $url = 'public/EventImgs/'.$evento->acronimo.$evento->edicion;
        // Guardar el archivo JSON en el sistema de ficheros
        Storage::put($url.'/'.$fileName, $jsonData);
        return true;
    }

    public function editParameterFile($eventoID){
        $evento = eventos::find($eventoID);
        $fileName = 'public/EventImgs/' . $evento->acronimo . $evento->edicion . '/' . $evento->acronimo . $evento->edicion . 'parameter.json';

        if (Storage::exists($fileName)) {
            $jsonData = Storage::get($fileName);
            $data = json_decode($jsonData, true);
        }
        return view('Eventos.parameters', ['parameters' => $data]);
    }

    public function updateParameterFile(request $request, $eventoID){
        $datos = $request->all();
        $evento = eventos::find($eventoID);
        // Actualizar los datos del JSON
        $data = [
            'Evento' => $evento->acronimo.' '.$evento->edicion,
            'MaxToApprove' => $datos['max_to_approve'],
            'MinToApprove' => $datos['min_to_approve'],
            'Questions' => $datos['questions'],
            'OptionAnswers' => $datos['answers'] // Mantener las respuestas sin cambios
        ];

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $fileName = 'public/EventImgs/'.$evento->acronimo.$evento->edicion.'/'.$evento->acronimo.$evento->edicion.'parameter.json';
        // Guardar el archivo JSON actualizado
        Storage::put($fileName, $jsonData);

        return redirect('eventos/'.$evento->id)->with('success','Se configuro el Evento Correctamente' );
        
    }

}