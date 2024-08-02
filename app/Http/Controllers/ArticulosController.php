<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\articulosAutores;
use App\Models\revisoresArticulos;
use App\Models\articulos;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\areas;

class ArticulosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($eventoId)
    {
        
        $evento = eventos::find($eventoId); 
        
        $Articulos = articulos::with(['evento', 'area', 'autores.usuario','revisores.usuario'])->where('evento_id', $evento->id)->OrderBy('id')->get();
        
        //Catalogo de Areas
        $Areas =areas::all();
        //Catalogo de Autores para el combo del Form "registrar Articulo"
        $Autores = articulosAutores::select('usuarios.id', 'usuarios.ap_paterno', 'usuarios.nombre', 'usuarios.ap_materno')
                    ->join('usuarios', 'articulos_autores.usuario_id', '=', 'usuarios.id')
                    ->groupBy('usuarios.id', 'usuarios.ap_paterno', 'usuarios.ap_materno','usuarios.nombre')
                    ->orderBy('usuarios.ap_paterno')
                    ->get();
        return view ('Articulos.index',compact('Articulos','Areas','Autores'));
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
        $evento= eventos::find($request->session()->get('eventoID'));
        if($evento){
            $datos=$request->all();

            if($request->has('pdf')){
                $area=areas::find($datos['area_id']);
                //validamos la carga del archivo
                $request->validate([
                    'pdf' => 'required|file|mimetypes:application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]);
                // manejo del archivo
                $archivo = $request->file('pdf');
                $nombreArchivo = $archivo->getClientOriginalName();
                try {
                    $archivo->storeAs('public/Lector/web/ArticulosporEvento/'.$evento->acronimo.$evento->edicion.'/'.$area->nombre.'/'.$datos['titulo'],$nombreArchivo);
                } catch (\Exception $e) {
                    return back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
                }
            }else{
                $nombreArchivo=null;
            }
            
            //insertamos datos del articulo
            $articulo = articulos::create([
                'evento_id'=>$evento->id,
                'titulo' => $datos['titulo'],
                'resumen' => $datos['resumen'],
                'archivo' => $nombreArchivo,
                'registro'=>getdate(),
                'area_id' => $datos['area_id'],
                'estado' => 'Recibido'
            ]);
    
            if ($request->has('selected_authors')) {
                $selectedAuthors = json_decode($request->input('selected_authors'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Recorrer el array de autores seleccionados
                    foreach ($selectedAuthors as $author) {
                        articulosAutores::create([
                            'evento_id'=>$evento->id,
                            'articulo_id'=>$articulo->id,
                            'usuario_id'=> $author['id'],
                            'correspondencia'=>$author['corresponding'],
                            'institucion'=>$author['institucion'],
                            'email'=>(usuarios::find($author['id']))->email
                        ]);
                    }
                } else {
                    echo "Error al decodificar Datos: ".json_last_error_msg();
                }
            }
    
            return redirect ($evento->id.'/articulos')->with('success','Articulo Registrado');
        }else{
            return redirect()->back()->with('error','No es posible agregar: el usuario no es parte del evento');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show($evento_id, $id)
    {
        $articulo = articulos::where('evento_id', $evento_id)->where('id', $id)->first();
        if(!is_null($articulo->archivo)){
            $pdfPath = 'SGEA/storage/app/public/Lector/web/viewer.html?file=ArticulosporEvento/'.
                        $articulo->evento->acronimo.$articulo->evento->edicion.'/'.$articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
            $pdfUrl =  asset($pdfPath);
        }else{
            $pdfUrl=null;
        }
        $autores= articulosAutores::where('articulo_id',$id)->OrderBy('orden')->get();
        $revisores= revisoresArticulos::where('articulo_id',$articulo->id)->get();
        
        return view ('Articulos.read',compact('articulo','pdfUrl','autores','revisores'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($evento_id, $id)
    {
        $articulo= articulos::where('evento_id', $evento_id)->where('id', $id)->first();
        $autores=articulosAutores::where('articulo_id',$articulo->id)->get();
    
        //catalogos
        $Areas = Areas::all();
        $Autores= articulosAutores::distinct('usuario_id')->get();
        return view('Articulos.edit', compact('articulo', 'Areas','autores','Autores'));
    }

    public function update(Request $request,$evento_id, $id)
    {

        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:200',
            'resumen' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
            'estado' => 'required|string|max:15',
        ]);
        //buscamos el articulo
        $articulo = articulos::where('evento_id', $evento_id)->where('id', $id)->firstOrFail();
        // manejo del archivo
        if($request->has('archivo')){
            
            $ArchivoAntiguo = 'public/Lector/web/ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                                $articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
            //verificamos si existe el archivo en nuestra carpeta destino 
            if (Storage::exists($ArchivoAntiguo)) {
                Storage::delete($ArchivoAntiguo);
            }
            //Guardamos el Nuevo Archivo
            $archivo = $request->file('archivo');
            $nombreArchivo = $archivo->getClientOriginalName();
            try {
                $archivo->storeAs('public/Lector/web/ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                            $articulo->area->nombre.'/'.$articulo->titulo , $nombreArchivo);
            } catch (\Exception $e) {
                return back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
            }
        }else{
           $nombreArchivo=$articulo->archivo;
       }
        //insertamos en articulo
        $articulo->update([
            'titulo'=> $validatedData['titulo'],
            'resumen'=> $validatedData['resumen'],
            'area_id'=> $validatedData['area_id'],
            'estado'=> $validatedData['estado'],
            'archivo'=> $nombreArchivo
        ]);

        if ($request->has('selected_authors')) {
            $selectedAuthors = json_decode($request->input('selected_authors'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Eliminar autores actuales
                articulosAutores::where('articulo_id', $articulo->id)->where('evento_id',$articulo->evento->id)->delete();
                foreach ($selectedAuthors as $author) {
                    articulosAutores::create([
                        'evento_id' => $evento_id,
                        'articulo_id' => $articulo->id,
                        'usuario_id' => $author['id'],
                        'correspondencia' => $author['corresponding'],
                        'institucion' => $author['institucion'],
                        'email' =>(usuarios::find($author['id']))->email
                    ]);
                }
            } else {
                echo "Error al decodificar Datos: ".json_last_error_msg();
            }
        }
        return redirect ($evento_id.'/articulos')->with('info','Informacion Actualizada');
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
        }
        if($articulo->estado !== "En revision" ){
            try {
                articulosAutores::where('articulo_id', $articulo->id)->where('evento_id',$articulo->evento->id)->delete();
                if($articulo->archivo !== null){
                    // Eliminar la Ruta asociada
                    $pdfPath = 'public/Lector/web/ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                                $articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
                    $folderPath = 'public/Lector/web/ArticulosporEvento/' . $articulo->evento->acronimo . $articulo->evento->edicion . '/' .
                                    $articulo->area->nombre.'/'.$articulo->titulo;
    
                    if (Storage::exists($pdfPath)) {
                        Storage::delete($pdfPath);
                    }
                    // Verificar si la carpeta está vacía y eliminarla si es así
                    if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                        Storage::deleteDirectory($folderPath);
                    }
                }
                $articulo->delete();
                return redirect()->back()->with('info', 'el artículo  se elimino correctamente');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }else{
            return redirect()->back()->with('error', 'El Articulo se encuentra en revisión');
        }
    }

    public function checkAuthor(Request $request)
     {
         $authorId = $request->input('author_id');
         $exists = articulosAutores::where('usuario_id', $authorId)->exists();
         $user = null;
         if (!$exists) {
             $user = usuarios::find($authorId);
         }else{
            $user =  articulosAutores::where('usuario_id', $authorId)->first();
         }
        return response()->json(['exists' => $exists, 'user' => $user]);
        
     }

     public function getArticles($area_id)
    {
        $articles = articulos::where('area_id', $area_id)->OrderBy('titulo')->get();
        return response()->json($articles);
    }
    
    //eliminacion masiva 
    public function deleteMultiple(Request $request){
    $ids = $request->ids;

    if (!empty($ids)) {
        foreach ($ids as $id) {
            // Buscar el artículo a eliminar
            $articulo = articulos::find($id);
            if (!$articulo) {
                return response()->json(['error' => "No se encontró el artículo con id: $id"], 404);
            }

            if ($articulo->estado !== "En revision") {
                try {
                    articulosAutores::where('articulo_id', $articulo->id)->where('evento_id', $articulo->evento->id)->delete();

                    if ($articulo->archivo !== null) {
                        // Eliminar la Ruta asociada
                        $pdfPath = 'public/Lector/web/ArticulosporEvento/' . $articulo->evento->acronimo . $articulo->evento->edicion . '/' .
                                   $articulo->area->nombre . '/' . $articulo->titulo . '/' . $articulo->archivo;
                        $folderPath = 'public/Lector/web/ArticulosporEvento/' . $articulo->evento->acronimo . $articulo->evento->edicion . '/' .
                                      $articulo->area->nombre . '/' . $articulo->titulo;

                        if (Storage::exists($pdfPath)) {
                            Storage::delete($pdfPath);
                        }
                        // Verificar si la carpeta está vacía y eliminarla si es así
                        if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                            Storage::deleteDirectory($folderPath);
                        }
                    }

                    $articulo->delete();
                } catch (\Exception $e) {
                    return response()->json(['error' => "Error al eliminar el artículo con id: $id. Detalle: " . $e->getMessage()], 500);
                }
            } else {
                return response()->json(['error' => "El artículo con id: $id se encuentra en revisión"], 403);
            }
        }

        return response()->json(['success' => "Registros eliminados correctamente."]);
    }

    return response()->json(['error' => "No se seleccionaron registros."], 400);
}
}
